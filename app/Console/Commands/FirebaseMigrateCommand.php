<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FirebaseMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'migrate:firebase
                            {--dry-run : Preview migration without saving data}
                            {--force : Overwrite existing records that were previously migrated}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate news data from Firebase Firestore to Laravel MySQL database';

    /**
     * Migration statistics.
     */
    protected array $stats = [
        'total' => 0,
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'failed' => 0,
    ];

    /**
     * Failed records for the summary report.
     */
    protected array $failures = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Check if Firebase Admin SDK is available
        if (!$this->checkFirebaseAvailability()) {
            return Command::FAILURE;
        }

        $isDryRun = $this->option('dry-run');
        $isForce = $this->option('force');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE: No data will be saved.');
            $this->newLine();
        }

        if ($isForce) {
            $this->warn('FORCE MODE: Existing records will be overwritten.');
            $this->newLine();
        }

        $this->info('Starting Firebase to Laravel migration...');
        $this->newLine();

        try {
            $documents = $this->fetchFirestoreDocuments();
        } catch (\Exception $e) {
            $this->error('Failed to fetch documents from Firebase: ' . $e->getMessage());
            Log::error('Firebase migration: Failed to fetch documents', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }

        $this->stats['total'] = count($documents);
        $this->info("Found {$this->stats['total']} document(s) in Firestore 'news' collection.");
        $this->newLine();

        $bar = $this->output->createProgressBar($this->stats['total']);
        $bar->start();

        foreach ($documents as $document) {
            try {
                $this->migrateDocument($document, $isDryRun, $isForce);
            } catch (\Exception $e) {
                $this->stats['failed']++;
                $firebaseId = $document['id'] ?? 'unknown';
                $this->failures[] = [
                    'firebase_id' => $firebaseId,
                    'error' => $e->getMessage(),
                ];
                Log::error('Firebase migration: Failed to migrate document', [
                    'firebase_id' => $firebaseId,
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->printSummary();

        return $this->stats['failed'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Check if Firebase Admin SDK is available.
     */
    protected function checkFirebaseAvailability(): bool
    {
        if (!class_exists(\Kreait\Firebase\Factory::class)) {
            $this->error('Firebase Admin SDK is not installed.');
            $this->error('Please install it with: composer require kreait/laravel-firebase');
            $this->newLine();
            $this->info('After installation, configure your Firebase credentials in .env:');
            $this->info('  FIREBASE_CREDENTIALS=/path/to/service-account.json');
            return false;
        }

        $credentialsPath = config('firebase.projects.app.credentials', env('FIREBASE_CREDENTIALS'));

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            $this->error('Firebase credentials file not found.');
            $this->error('Set FIREBASE_CREDENTIALS in your .env file to the path of your service account JSON.');
            return false;
        }

        return true;
    }

    /**
     * Fetch all documents from Firestore 'news' collection.
     *
     * @return array<int, array>
     */
    protected function fetchFirestoreDocuments(): array
    {
        $factory = (new \Kreait\Firebase\Factory())
            ->withServiceAccount(config('firebase.projects.app.credentials', env('FIREBASE_CREDENTIALS')));

        $firestore = $factory->createFirestore();
        $database = $firestore->database();

        $documents = [];
        $snapshot = $database->collection('news')->documents();

        foreach ($snapshot as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $documents[] = $data;
            }
        }

        return $documents;
    }

    /**
     * Migrate a single Firestore document to the news table.
     */
    protected function migrateDocument(array $document, bool $isDryRun, bool $isForce): void
    {
        $firebaseId = $document['id'];

        // Check if already migrated
        $existing = News::where('firebase_id', $firebaseId)->first();

        if ($existing && !$isForce) {
            $this->stats['skipped']++;
            return;
        }

        // Map category string to category_id
        $categoryId = $this->mapCategory($document['category'] ?? null);

        // Map author to user_id
        $authorId = $this->mapAuthor($document['author'] ?? null);

        // Download and store thumbnail
        $thumbnailPath = null;
        if (!empty($document['thumbnail']) || !empty($document['imageUrl'])) {
            $imageUrl = $document['thumbnail'] ?? $document['imageUrl'];
            $thumbnailPath = $this->downloadAndStoreThumbnail($imageUrl, $firebaseId);
        }

        // Prepare news data
        $title = $document['title'] ?? 'Untitled';
        $content = $document['content'] ?? '';
        $slug = Str::slug($title);

        // Ensure slug uniqueness
        $slugBase = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->where('firebase_id', '!=', $firebaseId)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $newsData = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => Str::limit(strip_tags($content), 200),
            'thumbnail' => $thumbnailPath,
            'category_id' => $categoryId,
            'author_id' => $authorId,
            'status' => 'published',
            'is_featured' => (bool) ($document['isFeatured'] ?? false),
            'is_breaking_news' => false,
            'views' => (int) ($document['views'] ?? 0),
            'published_at' => $this->parseTimestamp($document['createdAt'] ?? $document['publishedAt'] ?? null),
            'firebase_id' => $firebaseId,
        ];

        if ($isDryRun) {
            $this->stats[$existing ? 'updated' : 'created']++;
            return;
        }

        DB::beginTransaction();
        try {
            if ($existing && $isForce) {
                // Delete old thumbnail if different
                if ($existing->thumbnail && $existing->thumbnail !== $thumbnailPath) {
                    Storage::disk('public')->delete($existing->thumbnail);
                }
                $existing->update($newsData);
                $this->stats['updated']++;
            } else {
                News::create($newsData);
                $this->stats['created']++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Map a category string to a category_id.
     */
    protected function mapCategory(?string $categoryName): int
    {
        if (empty($categoryName)) {
            // Default to first category
            return Category::first()->id ?? 1;
        }

        $category = Category::where('name', 'LIKE', "%{$categoryName}%")
            ->orWhere('slug', Str::slug($categoryName))
            ->first();

        if ($category) {
            return $category->id;
        }

        // Create new category if not found
        $category = Category::create([
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
            'order' => 99,
        ]);

        return $category->id;
    }

    /**
     * Map an author string/email to a user_id.
     */
    protected function mapAuthor(?string $authorIdentifier): int
    {
        if (empty($authorIdentifier)) {
            // Default to first admin user
            return User::where('role', 'admin')->first()->id ?? 1;
        }

        // Try to find by email or name
        $user = User::where('email', $authorIdentifier)
            ->orWhere('name', 'LIKE', "%{$authorIdentifier}%")
            ->first();

        if ($user) {
            return $user->id;
        }

        // Default to first admin
        return User::where('role', 'admin')->first()->id ?? 1;
    }

    /**
     * Download thumbnail from Firebase Storage URL and store locally.
     */
    protected function downloadAndStoreThumbnail(string $url, string $firebaseId): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                Log::warning("Firebase migration: Failed to download thumbnail for {$firebaseId}", [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $content = $response->body();
            $extension = $this->guessExtension($response->header('Content-Type'));

            $path = sprintf(
                'thumbnails/%s/%s/%s.%s',
                now()->format('Y'),
                now()->format('m'),
                Str::random(20),
                $extension
            );

            Storage::disk('public')->put($path, $content);

            return $path;
        } catch (\Exception $e) {
            Log::warning("Firebase migration: Exception downloading thumbnail for {$firebaseId}", [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Guess file extension from content type.
     */
    protected function guessExtension(?string $contentType): string
    {
        return match ($contentType) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };
    }

    /**
     * Parse a Firebase timestamp to a Carbon instance.
     */
    protected function parseTimestamp(mixed $timestamp): ?\Illuminate\Support\Carbon
    {
        if (is_null($timestamp)) {
            return now();
        }

        // Firebase Timestamp object
        if (is_object($timestamp) && method_exists($timestamp, 'formatAsString')) {
            return \Illuminate\Support\Carbon::parse($timestamp->formatAsString());
        }

        // Array with seconds and nanoseconds (Firestore format)
        if (is_array($timestamp) && isset($timestamp['seconds'])) {
            return \Illuminate\Support\Carbon::createFromTimestamp($timestamp['seconds']);
        }

        // String date
        if (is_string($timestamp)) {
            return \Illuminate\Support\Carbon::parse($timestamp);
        }

        // Integer timestamp
        if (is_int($timestamp)) {
            return \Illuminate\Support\Carbon::createFromTimestamp($timestamp);
        }

        return now();
    }

    /**
     * Print the migration summary report.
     */
    protected function printSummary(): void
    {
        $this->info('=== Migration Summary ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Documents', $this->stats['total']],
                ['Created', $this->stats['created']],
                ['Updated (force)', $this->stats['updated']],
                ['Skipped (existing)', $this->stats['skipped']],
                ['Failed', $this->stats['failed']],
            ]
        );

        if (!empty($this->failures)) {
            $this->newLine();
            $this->error('Failed Records:');
            $this->table(
                ['Firebase ID', 'Error'],
                array_map(fn($f) => [$f['firebase_id'], Str::limit($f['error'], 80)], $this->failures)
            );
        }

        // Log summary
        Log::info('Firebase migration completed', $this->stats);
    }
}

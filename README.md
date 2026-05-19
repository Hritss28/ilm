# Info Lantas Mojokerto - Portal Berita

Portal berita berbasis Laravel untuk Info Lantas Mojokerto (ILM). Aplikasi ini menyediakan manajemen berita multi-role, galeri foto, video, dan halaman statis dengan optimasi SEO.

## Requirements

- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 20.x
- **NPM** >= 10.x
- **MySQL** >= 8.0
- **GD Extension** (untuk image processing)
- **Redis** (opsional, untuk caching)

### PHP Extensions yang Diperlukan

- `php-gd`
- `php-mbstring`
- `php-xml`
- `php-curl`
- `php-mysql`
- `php-zip`

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd laravel-ilm
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi berikut:

- **Database**: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- **App URL**: `APP_URL` (sesuaikan dengan domain production)
- **Storage**: `FILESYSTEM_DISK` (gunakan `public` untuk local, `s3` untuk AWS S3)
- **Cache**: `CACHE_STORE` (gunakan `file` atau `redis`)

### 4. Migrasi Database

```bash
php artisan migrate
```

### 5. Seed Data Awal

```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=StaticPageSeeder
```

Akun admin default:
- Email: `admin@infolantasmojokerto.com`
- Password: `password`

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

```bash
npm run build
```

### 8. Jalankan Aplikasi (Development)

```bash
php artisan serve
npm run dev
```

Akses di: `http://localhost:8000`

## Konfigurasi Production

### Storage (S3)

Untuk menggunakan AWS S3 sebagai storage:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

### Cache (Redis)

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Firebase Migration

Untuk migrasi data dari Firebase:

```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_PRIVATE_KEY_ID=your-key-id
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=your-service-account@project.iam.gserviceaccount.com
FIREBASE_CLIENT_ID=your-client-id
```

Jalankan migrasi:

```bash
php artisan migrate:firebase --dry-run   # Preview
php artisan migrate:firebase             # Eksekusi
php artisan migrate:firebase --force     # Overwrite existing
```

## Deployment

Gunakan script deployment yang tersedia:

```bash
chmod +x deploy.sh
./deploy.sh
```

Atau jalankan manual:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=CategorySeeder
php artisan storage:link
php artisan optimize
npm run build
```

## Scheduled Tasks

Tambahkan cron job berikut di server:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Task yang dijadwalkan:
- **Setiap jam**: Cleanup breaking news yang sudah expired

## Struktur Role

| Role      | Akses                                                    |
|-----------|----------------------------------------------------------|
| Admin     | Full access (semua fitur)                                |
| Redaktur  | News CRUD, publish, video, gallery                       |
| Author    | News create/edit (draft only, hanya milik sendiri)       |

## Testing

```bash
php artisan test
```

Untuk testing dengan data representatif:

```bash
php artisan db:seed --class=TestingSeeder
```

## Teknologi

- **Backend**: Laravel 13, PHP 8.3+
- **Frontend**: Blade Templates, TailwindCSS v3, Alpine.js
- **Database**: MySQL 8.x
- **Image Processing**: Intervention Image (GD Driver)
- **Asset Bundling**: Vite
- **Authentication**: Laravel Breeze

## Lisensi

Proprietary - Info Lantas Mojokerto

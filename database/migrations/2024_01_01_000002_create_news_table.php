<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->string('slug', 500)->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['draft', 'published', 'hidden'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_breaking_news')->default(false);
            $table->timestamp('breaking_news_until')->nullable();
            $table->bigInteger('views')->unsigned()->default(0);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('author_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_breaking_news');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

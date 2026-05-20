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
        Schema::create('info_lalins', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50); // Kemacetan, Penutupan jalan, dll
            $table->date('incident_date');
            $table->time('start_time');
            $table->time('estimated_end_time')->nullable();
            $table->string('status', 20)->default('Masih aktif'); // Masih aktif, Sudah selesai, Draft
            $table->string('title', 60);
            $table->string('description', 200);
            $table->string('alternative_route', 255)->nullable();
            $table->string('location', 50);
            $table->string('source', 100);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Drop lalin fields from news table since we didn't use them (count was 0)
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'lalin_category',
                'lalin_estimated_end',
                'lalin_status',
                'lalin_alternative_route',
                'lalin_location',
                'lalin_source',
                'lalin_contact'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('lalin_category')->nullable();
            $table->time('lalin_estimated_end')->nullable();
            $table->string('lalin_status')->nullable();
            $table->string('lalin_alternative_route')->nullable();
            $table->string('lalin_location')->nullable();
            $table->string('lalin_source')->nullable();
            $table->string('lalin_contact')->nullable();
        });

        Schema::dropIfExists('info_lalins');
    }
};

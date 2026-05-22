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
        Schema::table('news', function (Blueprint $table) {
            $table->string('lalin_category')->nullable()->after('status');
            $table->string('lalin_status')->nullable()->after('lalin_category');
            $table->timestamp('lalin_estimated_end')->nullable()->after('lalin_status');
            $table->text('lalin_alternative_route')->nullable()->after('lalin_estimated_end');
            $table->string('lalin_location')->nullable()->after('lalin_alternative_route');
            $table->string('lalin_source')->nullable()->after('lalin_location');
            $table->string('lalin_contact')->nullable()->after('lalin_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'lalin_category',
                'lalin_status',
                'lalin_estimated_end',
                'lalin_alternative_route',
                'lalin_location',
                'lalin_source',
                'lalin_contact',
            ]);
        });
    }
};

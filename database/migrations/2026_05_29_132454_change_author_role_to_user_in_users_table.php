<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, temporarily add the 'user' role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'redaktur', 'author', 'user') DEFAULT 'user'");
        
        // Update existing records
        DB::table('users')->where('role', 'author')->update(['role' => 'user']);
        
        // Remove 'author' from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'redaktur', 'user') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'redaktur', 'author', 'user') DEFAULT 'author'");
        DB::table('users')->where('role', 'user')->update(['role' => 'author']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'redaktur', 'author') DEFAULT 'author'");
    }
};

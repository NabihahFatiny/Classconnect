<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add columns FIRST
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('user_type')->default('student')->after('username');
        });

        // Backfill usernames (only matters if you already have users)
        // Use database-agnostic concatenation
        $connection = DB::connection()->getDriverName();
        if ($connection === 'pgsql') {
            DB::table('users')
                ->whereNull('username')
                ->orWhere('username', '')
                ->update(['username' => DB::raw("'user' || id::text")]);
        } else {
            DB::table('users')
                ->whereNull('username')
                ->orWhere('username', '')
                ->update(['username' => DB::raw("CONCAT('user', id)")]);
        }
        // First, add the columns to the table
        Schema::table('users', function (Blueprint $table) {
            // Add username column if it doesn't exist
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('name');
            }

            // Add other columns if they don't exist
            if (! Schema::hasColumn('users', 'mobile_phone')) {
                $table->string('mobile_phone')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('mobile_phone');
            }

            if (! Schema::hasColumn('users', 'user_id')) {
                $table->string('user_id')->nullable()->after('date_of_birth');
            }

            if (! Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['student', 'lecturer'])->default('student')->after('user_id');
            }
        });

        // Now update existing users to have unique usernames if they don't have one
        $users = \App\Models\User::where(function ($query) {
            $query->whereNull('username')->orWhere('username', '');
        })->get();

        foreach ($users as $user) {
            $baseUsername = strtolower(str_replace(' ', '_', $user->name));
            $username = $baseUsername;
            $counter = 1;

            // Ensure uniqueness
            while (\App\Models\User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername.'_'.$counter;
                $counter++;
            }

            $user->username = $username;
            $user->save();
        }

        // Add unique constraint to username (database-agnostic using Laravel's schema builder)
        if (Schema::hasColumn('users', 'username')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('username');
                });
            } catch (\Exception $e) {
                // Constraint might already exist, ignore the error
                // This is safe to ignore as the constraint is already in place
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['user_type', 'username']);
        });
    }
};

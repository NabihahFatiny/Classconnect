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
        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('lessons', 'description')) {
                $table->text('description')->after('title');
            }
            if (!Schema::hasColumn('lessons', 'file_path')) {
                $table->string('file_path')->nullable()->after('description');
            }
            if (!Schema::hasColumn('lessons', 'subject_id')) {
                $table->foreignId('subject_id')
                    ->after('file_path')
                    ->constrained('subjects')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'subject_id')) {
                try {
                    $table->dropForeign(['subject_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, ignore
                }
            }
            
            $columnsToDrop = [];
            if (Schema::hasColumn('lessons', 'title')) {
                $columnsToDrop[] = 'title';
            }
            if (Schema::hasColumn('lessons', 'description')) {
                $columnsToDrop[] = 'description';
            }
            if (Schema::hasColumn('lessons', 'file_path')) {
                $columnsToDrop[] = 'file_path';
            }
            if (Schema::hasColumn('lessons', 'subject_id')) {
                $columnsToDrop[] = 'subject_id';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

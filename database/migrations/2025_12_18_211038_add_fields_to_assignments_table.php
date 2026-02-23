<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('assignments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('subject_id');
            }
            if (!Schema::hasColumn('assignments', 'title')) {
                $table->string('title')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('assignments', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('assignments', 'due_at')) {
                $table->dateTime('due_at')->nullable()->after('description');
            }
            if (!Schema::hasColumn('assignments', 'max_marks')) {
                $table->integer('max_marks')->nullable()->after('due_at');
            }
            if (!Schema::hasColumn('assignments', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('max_marks');
            }

            // Foreign keys (optional but recommended)
            // If your subjects table uses "id" PK (usual), this works:
            $table->foreign('subject_id')->references('id')->on('subjects')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Drop FKs first (names may vary; try/catch is safest)
            try { $table->dropForeign(['subject_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['created_by']); } catch (\Throwable $e) {}

            $table->dropColumn([
                'subject_id','created_by','title','description','due_at','max_marks','attachment_path'
            ]);
        });
    }
};


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
            $table->unsignedBigInteger('created_by')->nullable();
        }

        if (!Schema::hasColumn('assignments', 'title')) {
            $table->string('title')->nullable();
        }

        if (!Schema::hasColumn('assignments', 'description')) {
            $table->text('description')->nullable();
        }

        if (!Schema::hasColumn('assignments', 'due_at')) {
            $table->dateTime('due_at')->nullable();
        }

        if (!Schema::hasColumn('assignments', 'max_marks')) {
            $table->integer('max_marks')->nullable();
        }

        if (!Schema::hasColumn('assignments', 'attachment_path')) {
            $table->string('attachment_path')->nullable();
        }
    });
}


    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'subject_id',
                'created_by',
                'title',
                'description',
                'due_at',
                'max_marks',
                'attachment_path',
            ]);
        });
    }
};


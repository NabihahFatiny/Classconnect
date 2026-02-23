<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('student_id');

            $table->string('file_path'); // storage/app/submissions/...
            $table->dateTime('submitted_at')->nullable();

            $table->string('status')->default('submitted'); // submitted | graded
            $table->boolean('is_late')->default(false);

            $table->timestamps();

            // Prevent duplicate submissions (one per assignment per student)
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('graded_by');

            $table->integer('marks')->nullable();
            $table->text('feedback')->nullable();
            $table->dateTime('graded_at')->nullable();

            $table->timestamps();

            // One grade per submission
            $table->unique('submission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

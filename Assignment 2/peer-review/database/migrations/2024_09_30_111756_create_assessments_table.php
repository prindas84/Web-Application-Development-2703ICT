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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('assessment_title', 20);
            $table->text('assessment_instruction');
            $table->unsignedInteger('reviews_required')->default(1);
            $table->unsignedInteger('max_score')->default(100)->check('max_score >= 1 and max_score <= 100');
            $table->enum('type', ['student-select', 'teacher-select']);
            $table->unsignedInteger('group_size')->default(0);
            $table->date('due_date');
            $table->time('due_time');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};

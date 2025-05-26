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
        Schema::create('m_rombel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('class_id')->nullable();
            $table->uuid('study_year_id')->nullable();
            $table->uuid('teacher_id')->nullable();
            $table->uuid('student_id')->nullable();
            $table->uuid('subject_schedule_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('class_id')->references('id')->on('m_class')->onDelete('cascade');
            $table->foreign('study_year_id')->references('id')->on('m_study_year')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('m_teacher')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('m_student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_rombel');
    }
};

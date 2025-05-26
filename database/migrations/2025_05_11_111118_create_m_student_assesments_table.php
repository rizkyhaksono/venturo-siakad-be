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
        Schema::create('m_student_assesments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('subject_schedule_id');
            $table->decimal('uts_score', 5, 2)->nullable();
            $table->decimal('uas_score', 5, 2)->nullable();
            $table->decimal('tugas_score', 5, 2)->nullable();
            $table->decimal('activity_score', 5, 2)->nullable();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->uuid('study_year_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('m_student')->onDelete('cascade');
            $table->foreign('subject_schedule_id')->references('id')->on('m_subject_schedule')->onDelete('cascade');
            $table->foreign('study_year_id')->references('id')->on('m_study_year')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_student_assesments');
    }
};

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
        Schema::create('m_subject_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_id');
            $table->uuid('subject_id');
            $table->uuid('teacher_id');
            $table->uuid('subject_hour_id');
            $table->string('day')->comment('Monday, Tuesday, Wednesday, Thursday, Friday');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('class_id')->references('id')->on('m_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('m_subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('m_teachers')->onDelete('cascade');
            $table->foreign('subject_hour_id')->references('id')->on('m_subject_hours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_subject_schedules');
    }
};

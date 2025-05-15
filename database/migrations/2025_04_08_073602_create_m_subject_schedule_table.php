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
        Schema::create('m_subject_schedule', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('teacher_id')->nullable();
            $table->uuid('subject_hour_id')->nullable();
            $table->uuid('rombel_id')->nullable();
            $table->string('day')->comment('Monday, Tuesday, Wednesday, Thursday, Friday');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('class_id')->references('id')->on('m_class')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('m_subject')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('m_teacher')->onDelete('cascade');
            $table->foreign('subject_hour_id')->references('id')->on('m_subject_hour')->onDelete('cascade');
            $table->foreign('rombel_id')->references('id')->on('m_rombel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_subject_schedule');
    }
};

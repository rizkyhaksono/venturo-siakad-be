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
        Schema::create('m_homeroom_teacher', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->uuid('class_id');
            $table->uuid('study_year_id');
            $table->string('semester')->comment('1, 2');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('teacher_id')->references('id')->on('m_teacher')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('m_class')->onDelete('cascade');
            $table->foreign('study_year_id')->references('id')->on('m_study_year')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_homeroom_teacher');
    }
};

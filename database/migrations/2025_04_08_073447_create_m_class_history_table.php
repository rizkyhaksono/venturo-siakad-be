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
        Schema::create('m_class_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('class_id');
            $table->uuid('study_year_id');
            $table->string('previous_status')->comment('entered, promoted, transferred');
            $table->string('new_status');
            $table->date('entry_date');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('student_id')->references('id')->on('m_student')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('m_class')->onDelete('cascade');
            $table->foreign('study_year_id')->references('id')->on('m_study_year')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_class_history');
    }
};

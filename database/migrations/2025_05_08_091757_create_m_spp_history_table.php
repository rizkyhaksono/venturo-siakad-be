<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_spp_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('spp_id');
            $table->integer('amount_paid');
            $table->date('payment_date');
            $table->string('payment_status')->comment('pending, paid, cancelled');
            $table->string('payment_method')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('m_user')->onDelete('cascade');
            $table->foreign('spp_id')->references('id')->on('m_spp')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_spp_history');
    }
};

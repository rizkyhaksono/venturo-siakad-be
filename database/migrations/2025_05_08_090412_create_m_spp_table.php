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
        Schema::create('m_spp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('jenis_biaya')->comment('Reguler, Mandiri');
            $table->string('semester_pembayaran')->comment('1, 2');
            $table->integer('total');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_spp');
    }
};

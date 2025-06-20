<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('setup_mast_count', function (Blueprint $table) {
            $table->string('countId')->primary();
            $table->unsignedBigInteger('countValue');
            $table->string('countDesc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setup_mast_count');
    }
};

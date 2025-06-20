<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('userId')->primary();
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('mobileNumber', 100)->unique();
            $table->string('emailAddress')->unique();
            $table->foreignId('genderId')->constrained('setup_gender', 'id')->onDelete('cascade');
            $table->foreignId('statusId')->constrained('setup_status', 'id')->onDelete('cascade');
            $table->string('passport')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

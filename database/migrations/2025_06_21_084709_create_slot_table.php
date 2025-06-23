<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locationId')->constrained('location', 'id')->onDelete('cascade');
            $table->string('slotName');
            $table->string('createdBy');
            $table->foreignId('statusId')->constrained('setup_status', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot');
    }
};

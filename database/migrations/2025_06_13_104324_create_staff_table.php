<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->string('staffId')->primary();
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

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id')->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_id')->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex(['tokenable_type', 'tokenable_id']); // Step 1: Drop composite index
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_id')->change(); // Step 2: Change column type
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->index(['tokenable_type', 'tokenable_id']); // Step 3: Re-add index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('business_type')->default('general');

            $table->foreignId('owner_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->string('logo')->nullable();

            $table->string('currency', 10)->default('BDT');

            $table->enum('status', [
                'active',
                'suspended',
                'pending',
            ])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
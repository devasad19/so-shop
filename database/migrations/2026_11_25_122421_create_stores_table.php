<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('phone')->nullable();

            $table->text('address')->nullable();

            $table->enum('store_type', [
                'shop',
                'warehouse',
            ])->default('shop');

            $table->boolean('is_default')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['business_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
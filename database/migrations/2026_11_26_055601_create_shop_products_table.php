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
Schema::create('shop_products', function (Blueprint $table) {
    $table->id();

    $table->foreignId('business_id')
        ->constrained('businesses')
        ->cascadeOnDelete();

    $table->foreignId('product_id')
        ->constrained('products')
        ->restrictOnDelete();

    $table->decimal('purchase_price', 15, 2)->default(0);
    $table->decimal('selling_price', 15, 2)->default(0);

    $table->decimal('current_stock', 18, 3)->default(0);
    $table->decimal('alert_quantity', 18, 3)->default(0);

    $table->boolean('track_stock')->default(true);
    $table->boolean('status')->default(true);

    $table->timestamps();

    $table->unique(['business_id', 'product_id']);

    $table->index(['business_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};

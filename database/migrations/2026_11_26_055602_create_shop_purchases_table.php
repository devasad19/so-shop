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
Schema::create('shop_purchases', function (Blueprint $table) {
    $table->id();

    $table->foreignId('business_id')
        ->constrained('businesses')
        ->cascadeOnDelete();

    $table->foreignId('shop_product_id')
        ->constrained('shop_products')
        ->restrictOnDelete();

    $table->decimal('pack_quantity', 15, 3);
    $table->decimal('pack_size', 15, 3);
    $table->decimal('total_quantity', 18, 3);

    $table->decimal('price_per_pack', 15, 2);
    $table->decimal('unit_cost', 15, 2);
    $table->decimal('total_amount', 15, 2);

    $table->string('pack_name')->nullable();
    $table->text('note')->nullable();

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();

    $table->index(['business_id', 'created_at']);
    $table->index('shop_product_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_purchases');
    }
};

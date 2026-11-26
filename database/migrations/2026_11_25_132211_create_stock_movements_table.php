
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
 Schema::create('stock_movements', function (Blueprint $table) {
    $table->id();

    $table->foreignId('business_id')
        ->constrained('businesses')
        ->cascadeOnDelete();

    $table->foreignId('shop_product_id')
        ->constrained('shop_products')
        ->restrictOnDelete();

    $table->enum('type', [
        'opening',
        'purchase',
        'sale',
        'sale_return',
        'purchase_return',
        'adjustment',
    ]);

    $table->enum('direction', ['in', 'out']);

    $table->decimal('quantity', 18, 3);
    $table->decimal('unit_cost', 15, 2)->default(0);

    $table->decimal('stock_before', 18, 3)->default(0);
    $table->decimal('stock_after', 18, 3)->default(0);

    $table->string('reference_type')->nullable();
    $table->unsignedBigInteger('reference_id')->nullable();

    $table->text('note')->nullable();

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();

    $table->index(['business_id', 'shop_product_id']);
    $table->index(['business_id', 'type']);
    $table->index(['reference_type', 'reference_id']);
    $table->index(['business_id', 'created_at']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

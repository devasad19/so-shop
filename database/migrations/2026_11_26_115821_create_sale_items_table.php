
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')
                ->constrained('sales')
                ->cascadeOnDelete();

            $table->foreignId('shop_product_id')
                ->constrained('shop_products')
                ->restrictOnDelete();

            $table->string('product_name');
            $table->string('unit_name')->nullable();

            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('purchase_cost', 12, 2)->default(0);

            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            $table->index('shop_product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};

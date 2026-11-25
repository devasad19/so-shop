
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained('businesses')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('product_categories')
                ->restrictOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->restrictOnDelete();

            $table->string('name');

            $table->string('sku')->nullable();

            $table->string('barcode')->nullable();

            $table->string('image')->nullable();

            $table->text('description')->nullable();

            $table->decimal('purchase_price', 15, 2)
                ->default(0);

            $table->decimal('selling_price', 15, 2)
                ->default(0);

            $table->decimal('current_stock', 18, 3)
                ->default(0);

            $table->decimal('alert_quantity', 18, 3)
                ->default(0);

            $table->boolean('track_stock')->default(true);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->unique(['business_id', 'sku']);
            $table->unique(['business_id', 'barcode']);

            $table->index(['business_id', 'name']);
            $table->index(['business_id', 'category_id']);
            $table->index(['business_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

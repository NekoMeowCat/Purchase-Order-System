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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prs_id')->nullable()->constrained('purchase_orders')->onDelete('cascade')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->string('po_number')->nullable();
            $table->string('po_date')->nullable();
            $table->string('product')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};

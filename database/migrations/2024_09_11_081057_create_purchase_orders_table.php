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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('budget_code')->nullable();
            $table->string('purpose')->nullable();
            $table->string('payee')->nullable();
            $table->string('pr_number');
            $table->string('quantity');
            $table->string('unit_no')->nullable();
            $table->text('description');
            $table->decimal('amount', 12, 2);
            $table->string('date_required')->nullable();
            $table->string('rejected_by')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('items_saved')->default(false);
            $table->string('prs_date');
            $table->decimal('total', 12, 2);
            $table->decimal('over_all_total', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opening_stock_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vendor_name')->nullable();
            $table->string('reference_no');
            $table->date('entry_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('opening_stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opening_stock_entry_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('hsn_sac', 50)->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('qty');
            $table->decimal('total_amount', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opening_stock_items');
        Schema::dropIfExists('opening_stock_entries');
    }
};

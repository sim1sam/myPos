<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('product_name');
            $table->string('hsn_sac', 50)->nullable();
            $table->unsignedInteger('qty');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_entries');
    }
};

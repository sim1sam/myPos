<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gst_rates', function (Blueprint $table) {
            $table->id();
            $table->string('hsn_sac')->unique();
            $table->string('description')->nullable();
            $table->enum('gst_type', ['simple', 'slab']);
            $table->decimal('simple_rate', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gst_rates');
    }
};

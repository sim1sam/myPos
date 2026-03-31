<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('mobile_number', 30)->nullable();
            $table->string('company_gstin', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pin', 20)->nullable();
            $table->string('state')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('ifsc_code', 30)->nullable();
            $table->string('company_pan', 30)->nullable();
            $table->text('declaration')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};

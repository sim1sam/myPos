<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code', 10)->nullable()->unique()->after('id');
        });

        $customers = DB::table('customers')->select('id', 'name')->orderBy('id')->get();
        $counters = [];

        foreach ($customers as $customer) {
            preg_match('/[A-Za-z]/', (string) $customer->name, $matches);
            $prefix = strtoupper($matches[0] ?? 'C');
            $counters[$prefix] = ($counters[$prefix] ?? 0) + 1;
            $code = $prefix . str_pad((string) $counters[$prefix], 4, '0', STR_PAD_LEFT);

            DB::table('customers')
                ->where('id', $customer->id)
                ->update(['customer_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['customer_code']);
            $table->dropColumn('customer_code');
        });
    }
};

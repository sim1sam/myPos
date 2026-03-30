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
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('vendor_code', 10)->nullable()->unique()->after('id');
        });

        $vendors = DB::table('vendors')->select('id', 'name')->orderBy('id')->get();
        $counters = [];

        foreach ($vendors as $vendor) {
            preg_match('/[A-Za-z]/', (string) $vendor->name, $matches);
            $prefix = strtoupper($matches[0] ?? 'V');
            $counters[$prefix] = ($counters[$prefix] ?? 0) + 1;
            $code = $prefix . str_pad((string) $counters[$prefix], 4, '0', STR_PAD_LEFT);

            DB::table('vendors')
                ->where('id', $vendor->id)
                ->update(['vendor_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropUnique(['vendor_code']);
            $table->dropColumn('vendor_code');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize any existing gateway values to 'paystack' so we remove flutterwave/cash
        DB::table('payments')->where('gateway', '!=', 'paystack')->update(['gateway' => 'paystack']);

        // Attempt to change the column to a plain string to avoid enum issues across DBs.
        try {
            Schema::table('payments', function (Blueprint $table) {
                // Requires doctrine/dbal for some drivers; if not available this will throw.
                $table->string('gateway', 50)->change();
            });
        } catch (\Throwable $e) {
            // If changing the column type is not possible in this environment, it's not fatal.
            // The important part is that existing values have been normalized above.
            // Log the exception when running migrations in your environment if needed.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We intentionally keep the down migration idempotent: set any non-paystack back to paystack.
        DB::table('payments')->where('gateway', '!=', 'paystack')->update(['gateway' => 'paystack']);
        // Reverting the column type change is left as a manual step because enum definitions
        // vary between database platforms.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('description'); // External payment gateway's transaction ID before payment
            }
            if (!Schema::hasColumn('transactions', 'currency')) {
                $table->string('currency', 10)->nullable()->after('payment_id'); // e.g., 'RUB', 'USD'
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency'); // e.g., 'WebMoney', 'Card'
            }
            if (!Schema::hasColumn('transactions', 'payment_system_trans_id')) {
                $table->string('payment_system_trans_id')->nullable()->after('payment_method'); // External payment gateway's transaction ID after payment
            }
            if (!Schema::hasColumn('transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_system_trans_id'); // Timestamp when the payment was confirmed
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
            if (Schema::hasColumn('transactions', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('transactions', 'payment_system_trans_id')) {
                $table->dropColumn('payment_system_trans_id');
            }
            if (Schema::hasColumn('transactions', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
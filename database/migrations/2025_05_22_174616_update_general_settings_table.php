<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Assuming you have a general_settings table
        // If not, you might create a new table or add to an existing one
        if (Schema::hasTable('general_settings')) {
            Schema::table('general_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('general_settings', 'webmoney_usd_to_rub_rate')) {
                    $table->decimal('webmoney_usd_to_rub_rate', 10, 4)->nullable()->after('yandex_api_key'); // Adjust precision as needed
                }
            });
        } else {
            // Or create a dedicated table for payment settings if preferred
            // Schema::create('payment_gateway_settings', function (Blueprint $table) {
            //     $table->id();
            //     $table->string('gateway_name');
            //     $table->string('setting_key');
            //     $table->string('setting_value');
            //     $table->timestamps();
            // });
        }

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'original_payment_amount')) {
                $table->decimal('original_payment_amount', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'original_payment_currency')) {
                $table->string('original_payment_currency', 3)->nullable()->after('original_payment_amount');
            }
            // The existing 'amount' column will store the converted RUB amount
            // The existing 'currency' column will remain 'RUB' for the converted amount
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('general_settings')) {
            Schema::table('general_settings', function (Blueprint $table) {
                if (Schema::hasColumn('general_settings', 'webmoney_usd_to_rub_rate')) {
                    $table->dropColumn('webmoney_usd_to_rub_rate');
                }
            });
        }

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'original_payment_amount')) {
                $table->dropColumn('original_payment_amount');
            }
            if (Schema::hasColumn('transactions', 'original_payment_currency')) {
                $table->dropColumn('original_payment_currency');
            }
        });
    }
};

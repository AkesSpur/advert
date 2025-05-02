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
        // Таблица для хранения баланса кошелька пользователя
        // Schema::table('users', function (Blueprint $table) {
        //     if (!Schema::hasColumn('users', 'balance')) {
        //         $table->decimal('balance', 10, 2)->default(0);
        //     }
        // });

        // Таблица для хранения тарифов рекламы
        Schema::create('ad_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название тарифа (Basic, Priority, VIP)
            $table->string('slug')->unique(); // Уникальный идентификатор тарифа
            $table->text('description')->nullable(); // Описание тарифа
            $table->decimal('base_price', 10, 2); // Базовая цена тарифа
            $table->string('billing_period'); // Период оплаты (day, week, month)
            $table->boolean('is_active')->default(true); // Активен ли тариф
            $table->timestamps();
        });

        // Таблица для хранения активных тарифов профилей
        Schema::create('profile_ad_tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_tariff_id')->constrained()->onDelete('cascade');
            $table->timestamp('starts_at'); // Дата начала действия тарифа
            $table->timestamp('expires_at')->nullable(); // Дата окончания действия тарифа
            $table->boolean('is_active')->default(true); // Активен ли тариф в данный момент
            $table->boolean('is_paused')->default(false); // Приостановлен ли тариф
            $table->integer('priority_level')->nullable(); // Уровень приоритета (для Priority тарифа)
            $table->integer('queue_position')->nullable(); // Позиция в очереди (для VIP тарифа)
            $table->decimal('daily_charge', 10, 2); // Ежедневная плата
            $table->timestamps();
        });

        // Таблица для хранения истории списаний за тарифы
        Schema::create('ad_tariff_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_ad_tariff_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Сумма списания
            $table->timestamp('charged_at'); // Дата списания
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_tariff_charges');
        Schema::dropIfExists('profile_ad_tariffs');
        Schema::dropIfExists('ad_tariffs');
    }
};
<?php

namespace Database\Seeders;

use App\Models\AdTariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Basic tariff
        AdTariff::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'description' => 'Стандартное размещение анкеты в общем списке. Ваша анкета будет видна всем пользователям в общем каталоге. Идеально для начала работы и привлечения первых клиентов без дополнительных затрат.',
            'base_price' => 100.00, // Daily price
            'billing_period' => 'day',
            'is_active' => true,
        ]);

        // Create Priority tariff
        AdTariff::create([
            'name' => 'Priority',
            'slug' => 'priority',
            'description' => 'Повышенный приоритет в результатах поиска. Чем выше уровень приоритета, тем выше ваша анкета будет отображаться в списке. Увеличьте видимость вашей анкеты и привлеките больше клиентов с помощью гибкой настройки уровня приоритета.',
            'base_price' => 200.00, // Base daily price, actual price depends on priority level
            'billing_period' => 'day',
            'is_active' => true,
        ]);

        // Create VIP tariff with fixed pricing
        AdTariff::create([
            'name' => 'VIP',
            'slug' => 'vip',
            'description' => 'Премиум размещение в топе всех категорий. Ваша анкета будет отображаться в специальном VIP-блоке в начале списка, что гарантирует максимальную видимость и привлечение наибольшего числа клиентов. Ограниченное количество мест (максимум 3 активных VIP анкеты одновременно) обеспечивает эксклюзивность и высокую эффективность.',
            'base_price' => 500.00, // Base price for reference
            'fixed_price' => 500.00, // Daily fixed price
            'weekly_price' => 3150.00, // Weekly price with 10% discount
            'monthly_price' => 12000.00, // Monthly price with 20% discount
            'billing_period' => 'fixed',
            'is_active' => true,
        ]);
    }
}
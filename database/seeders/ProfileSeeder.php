<?php

namespace Database\Seeders;

use App\Models\AdTariff;
use App\Models\MetroStation;
use App\Models\Neighborhood;
use App\Models\PaidService;
use App\Models\Profile;
use App\Models\ProfileAdTariff;
use App\Models\ProfileImage;
use App\Models\ProfileVideo;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user (user_id = 1)
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ]);
        }
        
        // Get all services, neighborhoods, metro stations, and paid services
        $services = Service::all();
        $neighborhoods = Neighborhood::all();
        $metroStations = MetroStation::all();
        $paidServices = PaidService::all();
        
        // Get tariffs
        $vipTariff = AdTariff::where('slug', 'vip')->first();
        $priorityTariff = AdTariff::where('slug', 'priority')->first();
        $basicTariff = AdTariff::where('slug', 'basic')->first();
        
        // Define common attributes
        $cities = ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань'];
        $hairColors = ['Блонд', 'Брюнет', 'Черный', 'Рыжий', 'Каштановый', 'Русый'];
        $tattoos = ['Нет', 'Маленькая', 'Средняя', 'Большая', 'Рукав'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        $districts = ['Центральный', 'Северный', 'Южный', 'Восточный', 'Западный'];
        
        // Create 30 profiles
        for ($i = 1; $i <= 30; $i++) {
            $name = 'Профиль ' . $i;
            $slug = Str::slug($name);
            $isActive = rand(0, 1) == 1; // 50% chance of being active
            $isVip = $i <= 3; // First 3 profiles are VIP
            $isVerified = rand(0, 1) == 1; // 50% chance of being verified
            
            // Create profile
            $profile = Profile::create([
                'user_id' => $user->id,
                'name' => $name,
                'slug' => $slug,
                'age' => rand(21, 45),
                'weight' => rand(45, 80),
                'height' => rand(150, 190),
                'size' => $sizes[array_rand($sizes)],
                'bio' => 'Это биография для ' . $name,
                'metro_station' => $metroStations->isNotEmpty() ? $metroStations->random()->name : null,
                'district' => $districts[array_rand($districts)],
                'status' => $isActive,
                'is_active' => $isActive,
                'city' => $cities[array_rand($cities)],
                'phone' => '+7' . rand(9000000000, 9999999999),
                'description' => 'Подробное описание для ' . $name . '. Здесь может быть размещена информация о предоставляемых услугах, опыте работы и других деталях.',
                'hair_color' => $hairColors[array_rand($hairColors)],
                'tattoo' => $tattoos[array_rand($tattoos)],
                'telegram' => '@' . strtolower(str_replace(' ', '', $name)),
                'viber' => '+7' . rand(9000000000, 9999999999),
                'whatsapp' => '+7' . rand(9000000000, 9999999999),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'payment_wmz' => rand(0, 1) == 1,
                'payment_card' => rand(0, 1) == 1,
                'payment_sbp' => rand(0, 1) == 1,
                'pricing' => json_encode([
                    'standard' => rand(100, 300) * 10,
                    'premium' => rand(300, 600) * 10,
                    'vip' => rand(600, 1000) * 10,
                ]),
                'profile_type' => rand(0, 1) == 1 ? 'individual' : 'salon',
                'vyezd' => rand(0, 1) == 1,
                'appartamenti' => rand(0, 1) == 1,
                'vyezd_1hour' => rand(100, 300) * 10,
                'vyezd_2hours' => rand(200, 500) * 10,
                'vyezd_night' => rand(500, 1000) * 10,
                'appartamenti_1hour' => rand(100, 300) * 10,
                'appartamenti_2hours' => rand(200, 500) * 10,
                'appartamenti_night' => rand(500, 1000) * 10,
                'is_vip' => $isVip,
                'is_verified' => $isVerified,
                'views_count' => rand(10, 1000),
                'clicks_count' => rand(5, 500),
            ]);
            
            // Assign random services to profile (2-4 services)
            if ($services->isNotEmpty()) {
                $profileServiceCount = min(rand(2, 4), $services->count());
                $selectedServices = $services->random($profileServiceCount);
                $profile->services()->attach($selectedServices);
            }
            
            // Assign random neighborhoods to profile (1-3 neighborhoods)
            if ($neighborhoods->isNotEmpty()) {
                $profileNeighborhoodCount = min(rand(1, 3), $neighborhoods->count());
                $selectedNeighborhoods = $neighborhoods->random($profileNeighborhoodCount);
                $profile->neighborhoods()->attach($selectedNeighborhoods);
            }
            
            // Assign random paid services to profile (3-8 paid services)
            if ($paidServices->isNotEmpty()) {
                $profilePaidServiceCount = min(rand(3, 8), $paidServices->count());
                $selectedPaidServices = $paidServices->random($profilePaidServiceCount);
                
                foreach ($selectedPaidServices as $paidService) {
                    $profile->paidServices()->attach($paidService, [
                        'price' => rand(50, 300) * 10,
                        'is_active' => rand(0, 1) == 1,
                    ]);
                }
            }
            
            // Create profile images (2-5 images)
            $imageCount = rand(2, 5);
            for ($j = 1; $j <= $imageCount; $j++) {
                ProfileImage::create([
                    'profile_id' => $profile->id,
                    'path' => 'images/profiles/' . $slug . '/' . $j . '.jpg',
                    'is_primary' => $j == 1, // First image is primary
                    'sort_order' => $j,
                ]);
            }
            
            // Create profile videos (0-2 videos)
            $videoCount = rand(0, 2);
            for ($j = 1; $j <= $videoCount; $j++) {
                ProfileVideo::create([
                    'profile_id' => $profile->id,
                    'path' => 'videos/profiles/' . $slug . '/' . $j . '.mp4',
                    'thumbnail_path' => 'videos/profiles/' . $slug . '/thumbnails/' . $j . '.jpg',
                ]);
            }
            
            // Assign ad tariffs to profiles
            // First 3 profiles get VIP tariff
            // Next 10 profiles get Priority tariff
            // Rest get Basic tariff
            if ($i <= 3 && $vipTariff) {
                $tariff = $vipTariff;
                $priorityLevel = null;
                $queuePosition = $i;
                $dailyCharge = $vipTariff->base_price;
            } elseif ($i <= 13 && $priorityTariff) {
                $tariff = $priorityTariff;
                $priorityLevel = rand(1, 5);
                $queuePosition = null;
                $dailyCharge = $priorityTariff->base_price;
            } elseif ($basicTariff) {
                $tariff = $basicTariff;
                $priorityLevel = null;
                $queuePosition = null;
                $dailyCharge = $basicTariff->base_price;
            } else {
                continue; // Skip if tariff not found
            }
            
            ProfileAdTariff::create([
                'profile_id' => $profile->id,
                'ad_tariff_id' => $tariff->id,
                'starts_at' => now()->subDays(rand(1, 30)),
                'expires_at' => now()->addDays(rand(1, 30)),
                'is_active' => $isActive,
                'is_paused' => rand(0, 10) == 0, // 10% chance of being paused
                'priority_level' => $priorityLevel,
                'queue_position' => $queuePosition,
                'daily_charge' => $dailyCharge,
            ]);
        }
    }
}
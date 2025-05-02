<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $adminUser = User::where('role', 'admin')->first();
        
        // Get all profiles
        $profiles = Profile::all();
        
        // Create reviews, messages, transactions, and advertisements for each profile
        foreach ($profiles as $profile) {
            // Create some reviews for each profile (0-5 reviews)
            $reviewCount = rand(0, 5);
            for ($j = 1; $j <= $reviewCount; $j++) {
                // Create a random user for each review
                $reviewUser = User::factory()->create([
                    'name' => 'User ' . $profile->id . '-' . $j,
                    'email' => 'user' . $profile->id . '-' . $j . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'client',
                ]);

                Review::create([
                    'user_id' => $reviewUser->id,
                    'profile_id' => $profile->id,
                    'rating' => rand(3, 5), // Ratings between 3-5
                    'comment' => 'Это отзыв для ' . $profile->name . ' от пользователя ' . $j,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Create some messages for each profile (0-10 messages)
            $messageCount = rand(0, 10);
            for ($j = 1; $j <= $messageCount; $j++) {
                // Create a random user for each message if not already created
                $messageUserEmail = 'user' . $profile->id . '-' . ceil($j/2) . '@example.com';
                $messageUser = User::where('email', $messageUserEmail)->first();
                
                if (!$messageUser) {
                    $messageUser = User::factory()->create([
                        'name' => 'User ' . $profile->id . '-' . ceil($j/2),
                        'email' => $messageUserEmail,
                        'password' => Hash::make('password'),
                        'role' => 'client',
                    ]);
                }

                // Alternate between sent and received messages
                $senderId = $j % 2 === 0 ? $adminUser->id : $messageUser->id;
                $recipientId = $j % 2 === 0 ? $messageUser->id : $adminUser->id;

                Message::create([
                    'sender_id' => $senderId,
                    'recipient_id' => $recipientId,
                    'content' => 'Это сообщение ' . $j . ' между админом и пользователем для профиля ' . $profile->name,
                    'is_read' => rand(0, 1) === 1,
                    'created_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 23)),
                    'updated_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 23)),
                ]);
            }

            // Create some transactions for each profile (1-3 transactions)
            $transactionCount = rand(1, 3);
            for ($j = 1; $j <= $transactionCount; $j++) {
                $amount = rand(10, 100) * 10;
                $type = rand(0, 1) === 0 ? 'topup' : 'purchase';
                $status = rand(0, 10) <= 8 ? 'completed' : (rand(0, 1) === 0 ? 'pending' : 'failed'); // 80% completed, 10% pending, 10% failed

                Transaction::create([
                    'user_id' => $adminUser->id,
                    'amount' => $amount,
                    'type' => $type,
                    'status' => $status,
                    'reference_id' => Str::random(10),
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Create some advertisements (0-2 advertisements)
            $advertisementCount = rand(0, 2);
            for ($j = 1; $j <= $advertisementCount; $j++) {
                Advertisement::create([
                    'user_id' => $adminUser->id,
                    'title' => 'Объявление ' . $j . ' для ' . $profile->name,
                    'content' => 'Это содержание объявления ' . $j . ' для профиля ' . $profile->name,
                    'budget' => rand(100, 1000) * 10,
                    'status' => ['pending', 'active', 'completed'][rand(0, 2)],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
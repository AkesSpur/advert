<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUsersLastActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-last-active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update last_active timestamp for users who have null values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('last_active')->get();
        
        $count = 0;
        foreach ($users as $user) {
            // Use updated_at if available, otherwise use created_at
            $lastActive = $user->updated_at ?: $user->created_at;
            
            $user->update(['last_active' => $lastActive]);
            $count++;
        }
        
        $this->info("Updated last_active timestamp for {$count} users.");
        
        return Command::SUCCESS;
    }
}
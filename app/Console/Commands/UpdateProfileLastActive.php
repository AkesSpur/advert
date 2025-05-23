<?php

namespace App\Console\Commands;

use App\Models\Profile;
use Illuminate\Console\Command;

class UpdateProfileLastActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:update-last-active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update last_active timestamp for profiles that have null values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating last_active timestamps for profiles...');
        
        // Get all profiles with null last_active
        $profiles = Profile::whereNull('last_active')->get();
        $count = $profiles->count();
        
        if ($count == 0) {
            $this->info('No profiles need updating.');
            return 0;
        }
        
        $this->info("Found {$count} profiles to update.");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($profiles as $profile) {
            // If profile has updated_at, use that, otherwise use created_at
            $lastActive = $profile->updated_at ?? $profile->created_at;
            $profile->update(['last_active' => $lastActive]);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('All profiles have been updated successfully!');
        
        return 0;
    }
}
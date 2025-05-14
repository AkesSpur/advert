<?php

namespace Database\Seeders;

use App\Models\FooterMenu;
use App\Models\TopMenu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $flags = [
            'all_accounts',
            'has_video',
            'verified',
            'vip',
            'cheapest',
            'new',
        ];

        foreach ($flags as $flag) {
            FooterMenu::create([
                'name' => ucfirst(str_replace('_', ' ', $flag)),
                $flag => true,
            ]);
        }

        foreach ($flags as $flag) {
            TopMenu::create([
                'name' => ucfirst(str_replace('_', ' ', $flag)),
                $flag => true,
            ]);
        }
    }
}

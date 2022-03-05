<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create();
        Product::factory(10)->create();
        Category::factory(2)->create();
        
        Setting::create([
            'name' => 'client-key',
            'value' => 'SB-Mid-client-ZK801qB3t6GSnZl0'
        ]);
        Setting::create([
            'name' => 'server-key',
            'value' => 'SB-Mid-server-G9aehKBKb4BtBndGVjBVabhv'
        ]);
        Setting::create([
            'name' => 'latitude',
            'value' => '-3.44592'
        ]);
        Setting::create([
            'name' => 'longitude',
            'value' => '114.84437'
        ]);

    }
}

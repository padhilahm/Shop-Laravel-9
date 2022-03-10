<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\ShippingPrice;
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
        Category::factory(1)->create();
        
        Setting::create([
            'name' => 'shop-name',
            'value' => 'TokoMe'
        ]);
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
        Setting::create([
            'name' => 'address',
            'value' => 'Banjarbaru'
        ]);
        Setting::create([
            'name' => 'shipping-max',
            'value' => '20'
        ]);
        Setting::create([
            'name' => 'shipping-price',
            'value' => '5000'
        ]);
        Setting::create([
            'name' => 'email',
            'value' => 'padhilahm@padhil.my.id'
        ]);
        Setting::create([
            'name' => 'smtp-host',
            'value' => 'mail.padhil.my.id'
        ]);
        Setting::create([
            'name' => 'smtp-port',
            'value' => '465'
        ]);
        Setting::create([
            'name' => 'password',
            'value' => 'HIL~U)gSa_dz'
        ]);
        Setting::create([
            'name' => 'delivered',
            'value' => 'true'
        ]);
        Setting::create([
            'name' => 'take',
            'value' => 'true'
        ]);
        Setting::create([
            'name' => 'cod',
            'value' => 'true'
        ]);
        Setting::create([
            'name' => 'direct',
            'value' => 'true'
        ]);

        ShippingPrice::create([
            'distince' => 0,
            'price' => 0
        ]);
        ShippingPrice::create([
            'distince' => 5,
            'price' => 5000
        ]);
        ShippingPrice::create([
            'distince' => 10,
            'price' => 10000
        ]);
        ShippingPrice::create([
            'distince' => 15,
            'price' => 15000
        ]);
    }
}

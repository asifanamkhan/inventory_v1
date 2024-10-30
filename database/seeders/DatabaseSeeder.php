<?php

namespace Database\Seeders;

use App\Livewire\Dashboard\Admin\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        DB::table('company')->insert([
            'name' => 'Inventory',
            'short_name' => 'Inventory',
            'email' => 'inventory@gmail.com',
            'phone' => '01643734728',
            'created_by' => '1',
            'address' => 'house-no-1, road-1, city-1, country-1',
        ]);

        DB::table('branch')->insert([
            'name' => 'Main branch',
            'email' => 'main@gmail.com',
            'phone' => '01643734728',
            'created_by' => '1',
            'address' => 'house-no-1, road-1, city-1, country-1',
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ]);
    }
}
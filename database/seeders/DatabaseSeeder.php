<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user_generator_rules = [
            'table' => 'users',
            'length' => '10',
            'prefix' => date('ymd'),
        ];
        User::create([
            'id' => IdGenerator::generate($user_generator_rules),
            'email' => 'admin@mail.com',
            'user_type' => 1,
            'first_name' => 'Admin',
            'password' => Hash::make('admin1234'),
        ]);
        User::create([
            'id' => IdGenerator::generate($user_generator_rules),
            'email' => 'user@mail.com',
            'user_type' => 0,
            'first_name' => 'TestUserOne',
            'password' => Hash::make('user1234'),
        ]);

        Pages::create([
            "name" => "News",
            "name_slug" => "news",
            "logo_img" => "",
            "wallpaper_img" => "",
        ]);

    }
}

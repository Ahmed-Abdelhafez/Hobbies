<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User([
            'name' => 'Ahmed Emad',
            'email' => 'a.emad23545@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('ahmed1234'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);

        $admin->save();
    }
}

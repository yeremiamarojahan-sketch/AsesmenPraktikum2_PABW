<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@foodshare.test'],
            [
                'name'     => 'Admin FoodShare',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'yeon@foodshare.test'],
            [
                'name'     => 'Yeon',
                'password' => Hash::make('password123'),
                'role'     => 'pemohon',
            ]
        );
        User::updateOrCreate(
            ['email' => 'Yeremia@foodshare.test'],
            [
                'name'     => 'Yeremia',
                'password' => Hash::make('password123'),
                'role'     => 'pemohon',
            ]
        );
    }
}
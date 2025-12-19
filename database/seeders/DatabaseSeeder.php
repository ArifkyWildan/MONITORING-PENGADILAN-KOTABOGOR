<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Bagian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full access to all features'
            ],
            [
                'name' => 'verifikator',
                'display_name' => 'Verifikator',
                'description' => 'Can verify isian submissions'
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'description' => 'Can view all data and statistics'
            ],
            [
                'name' => 'user',
                'display_name' => 'User Biasa',
                'description' => 'Can only view list isian'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Seed Users - UPDATED EMAIL
        $users = [
            [
                'name' => 'Admin System',
                'email' => 'admin@pnbogor.go.id', // CHANGED: Removed ".go"
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'admin')->first()->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator 1',
                'email' => 'verifikator@pnbogor.go.id', // CHANGED
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'verifikator')->first()->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pimpinan PN',
                'email' => 'pimpinan@pnbogor.go.id', // CHANGED
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'pimpinan')->first()->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Biasa',
                'email' => 'user@pnbogor.go.id', // CHANGED
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'user')->first()->id,
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Seed Bagians
        $bagians = [
            'Ketua',
            'Wakil Ketua',
            'Sekertaris',
            'Panitera',
            'Perdata',
            'Pidana',
            'Hukum',
            'Kepegawaian',
            'Umum & Keuangan',
            'PTIP'
        ];

        foreach ($bagians as $bagian) {
            Bagian::create([
                'nama' => $bagian,
                'slug' => Str::slug($bagian)
            ]);
        }
    }
}
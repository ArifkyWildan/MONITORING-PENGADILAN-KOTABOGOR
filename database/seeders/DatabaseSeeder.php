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
        // ===== SEED ROLES ===== 
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
                'display_name' => 'User',
                'description' => 'Can create and manage own isian'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $verifikatorRoleId = Role::where('name', 'verifikator')->first()->id;
        $pimpinanRoleId = Role::where('name', 'pimpinan')->first()->id;

        // ===== SEED BAGIANS FIRST =====
        $bagianData = [
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

        $bagians = [];
        foreach ($bagianData as $nama) {
            $bagians[$nama] = Bagian::create([
                'nama_bagian' => $nama,
                'slug' => Str::slug($nama)
            ]);
        }

        // ===== SEED ADMIN USERS =====
        $adminUsers = [
            [
                'name' => 'Ketua',
                'email' => 'ketua@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'bagian_id' => null, // Admin tidak terikat bagian
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Wakil Ketua',
                'email' => 'wakilketua@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'bagian_id' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sekertaris',
                'email' => 'sekertaris@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'bagian_id' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Panitera',
                'email' => 'panitera@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'bagian_id' => null,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($adminUsers as $user) {
            User::create($user);
        }

        // ===== SEED VERIFIKATOR PER BAGIAN =====
        $verifikatorUsers = [
            [
                'name' => 'Verifikator Perdata',
                'email' => 'verifikator.perdata@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['Perdata']->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator Pidana',
                'email' => 'verifikator.pidana@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['Pidana']->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator Hukum',
                'email' => 'verifikator.hukum@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['Hukum']->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator Kepegawaian',
                'email' => 'verifikator.kepegawaian@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['Kepegawaian']->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator Umum & Keuangan',
                'email' => 'verifikator.umum@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['Umum & Keuangan']->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator PTIP',
                'email' => 'verifikator.ptip@pnbogor.go.id',
                'password' => Hash::make('password'),
                'role_id' => $verifikatorRoleId,
                'bagian_id' => $bagians['PTIP']->id,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($verifikatorUsers as $user) {
            User::create($user);
        }

        // ===== SEED PIMPINAN =====
        User::create([
            'name' => 'Pimpinan PN',
            'email' => 'pimpinan@pnbogor.go.id',
            'password' => Hash::make('password'),
            'role_id' => $pimpinanRoleId,
            'bagian_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('=== ADMIN USERS ===');
        $this->command->info('1. Ketua - ketua@pnbogor.go.id');
        $this->command->info('2. Wakil Ketua - wakilketua@pnbogor.go.id');
        $this->command->info('3. Sekertaris - sekertaris@pnbogor.go.id');
        $this->command->info('4. Panitera - panitera@pnbogor.go.id');
        $this->command->info('');
        $this->command->info('=== VERIFIKATOR PER BAGIAN ===');
        $this->command->info('1. Verifikator Perdata - verifikator.perdata@pnbogor.go.id');
        $this->command->info('2. Verifikator Pidana - verifikator.pidana@pnbogor.go.id');
        $this->command->info('3. Verifikator Hukum - verifikator.hukum@pnbogor.go.id');
        $this->command->info('4. Verifikator Kepegawaian - verifikator.kepegawaian@pnbogor.go.id');
        $this->command->info('5. Verifikator Umum & Keuangan - verifikator.umum@pnbogor.go.id');
        $this->command->info('6. Verifikator PTIP - verifikator.ptip@pnbogor.go.id');
        $this->command->info('');
        $this->command->info('All passwords: password');
    }
}
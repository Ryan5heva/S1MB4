<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@simba.test'],
            [
                'name'     => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@simba.test'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Operator 1–4
        $operators = [
            ['name' => 'Operator Satu',   'email' => 'operator1@simba.test'],
            ['name' => 'Operator Dua',    'email' => 'operator2@simba.test'],
            ['name' => 'Operator Tiga',   'email' => 'operator3@simba.test'],
            ['name' => 'Operator Empat',  'email' => 'operator4@simba.test'],
        ];

        foreach ($operators as $op) {
            User::updateOrCreate(
                ['email' => $op['email']],
                [
                    'name'     => $op['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'operator',
                ]
            );
        }

        // ─── PPID Seeders ───
        $this->call([
            PpidInformasiBerkalaSeeder::class,
            PpidInformasiSertaMertaSeeder::class,
            PpidInformasiSetiapSaatSeeder::class,
            PpidInformasiDikecualikanSeeder::class,
        ]);
    }
}

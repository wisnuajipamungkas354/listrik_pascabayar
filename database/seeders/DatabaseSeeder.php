<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Level::create(['nama_level' => 'admin']);
        Level::create(['nama_level' => 'petugas']);

        User::create([
            'name' => 'Wisnu Aji Pamungkas',
            'username' => 'wisnuaji354',
            'password' => Hash::make('password'),
            'level_id' => 1
        ]);

        User::create([
            'name' => 'Agus Kurniawan',
            'username' => 'aguskurniawan123',
            'password' => Hash::make('password'),
            'level_id' => 2
        ]);

        DB::table('tarifs')->insert([
            [
                'daya' => 450,
                'tarifperkwh' => 415,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'daya' => 900,
                'tarifperkwh' => 605,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'daya' => 1300,
                'tarifperkwh' => 1444,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'daya' => 2200,
                'tarifperkwh' => 1444,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'daya' => 3500,
                'tarifperkwh' => 1699,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'daya' => 5500,
                'tarifperkwh' => 1699,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('pelanggans')->insert([
            [
                'username'       => 'andi2024',
                'password'       => Hash::make('password'),
                'nomor_kwh'      => '123456789001',
                'nama_pelanggan' => 'Andi Pratama',
                'alamat'         => 'Jl. Melati No. 10',
                'tarif_id'       => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'username'       => 'budi9901',
                'password'       => Hash::make('password'),
                'nomor_kwh'      => '123456789002',
                'nama_pelanggan' => 'Budi Santoso',
                'alamat'         => 'Jl. Kenanga No. 5',
                'tarif_id'       => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'username'       => 'citra8812',
                'password'       => Hash::make('password'),
                'nomor_kwh'      => '123456789003',
                'nama_pelanggan' => 'Citra Lestari',
                'alamat'         => 'Jl. Anggrek No. 21',
                'tarif_id'       => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'username'       => 'dina2025',
                'password'       => Hash::make('password'),
                'nomor_kwh'      => '123456789004',
                'nama_pelanggan' => 'Dina Maharani',
                'alamat'         => 'Jl. Mawar No. 8',
                'tarif_id'       => 4,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'username'       => 'eko77123',
                'password'       => Hash::make('password'),
                'nomor_kwh'      => '123456789005',
                'nama_pelanggan' => 'Eko Saputra',
                'alamat'         => 'Jl. Dahlia No. 3',
                'tarif_id'       => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}

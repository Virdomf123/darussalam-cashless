<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        \App\Models\Santri::truncate();

        \App\Models\Santri::create([
            'nama' => 'Virdo',
            'nim'  => '5829',
            'saldo' => 500000,
        ]);

        // Data Santri Tambahan 1
        \App\Models\Santri::create([
            'nama' => 'Ahmad Zaki',
            'nim' => '1234',
            'saldo' => 75000
        ]);

        // Data Santri Tambahan 2
        \App\Models\Santri::create([
            'nama' => 'Siti Aminah',
            'nim' => '5678',
            'saldo' => 200000
        ]);
    }
}

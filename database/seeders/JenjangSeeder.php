<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenjangs = [
            ['nama' => 'KB', 'seksi' => 'paud'],
            ['nama' => 'TK', 'seksi' => 'paud'],
            ['nama' => 'PAUD', 'seksi' => 'paud'],
            ['nama' => 'SPS', 'seksi' => 'paud'],
            ['nama' => 'TPA', 'seksi' => 'paud'],
            ['nama' => 'PKBM', 'seksi' => 'dikmas'],
            ['nama' => 'LKP', 'seksi' => 'dikmas'],
        ];

        foreach ($jenjangs as $j) {
            \App\Models\Jenjang::firstOrCreate(
                ['nama' => $j['nama']],
                ['seksi' => $j['seksi'], 'is_active' => true]
            );
        }
    }
}

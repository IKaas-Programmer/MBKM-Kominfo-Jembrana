<?php

namespace Database\Seeders;


use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Memanggil kelas UserSeeder yang barusan kita buat
        $this->call([
            UserSeeder::class,
        ]);
    }
}
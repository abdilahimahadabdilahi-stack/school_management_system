<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        // Waxay database-ka ku shubaysaa 10 maamule oo fake ah
        Manager::factory(10)->create();
    }
}
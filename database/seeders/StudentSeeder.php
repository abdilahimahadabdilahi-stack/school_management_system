<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Habka 1-aad: Isticmaalka Factory si 10 arday oo tijaabo ah loo abuurayo
        Student::factory(10)->create();

        // Habka 2-aad: Haddii aad rabto inaad gacanta ku qorto arday gaar ah
        /*
        Student::create([
            'name' => 'Jama Ali',
            'age' => 20,
            'email' => 'jama@gmail.com',
            'class_name' => 'Class A',
        ]);
        */
    }
}
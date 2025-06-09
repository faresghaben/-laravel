<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Patient;
use App\Models\User; // لأن المريض مرتبط بمستخدم

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // جلب المستخدمين الذين ليسوا مرضى أو أطباء بعد
        $usersWithoutRoles = User::doesntHave('patient')->doesntHave('doctor')->get();

        // إنشاء مرضى من المستخدمين المتاحين
        foreach ($usersWithoutRoles as $user) {
            // يمكن تعيين عدد عشوائي من المرضى لضمان وجود بيانات
            if (rand(0, 1)) { // 50% فرصة لجعل المستخدم مريضاً
                Patient::create([
                    'user_id' => $user->id,
                    'name' => $faker->name(),
                    'date_of_birth' => $faker->date('Y-m-d', '2000-01-01'),
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                    'allergies' => $faker->optional(0.7)->word() . ', ' . $faker->optional(0.3)->word(),
                    
                ]);
            }
        }
    }
}
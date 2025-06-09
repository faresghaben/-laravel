<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Doctor; // تأكد من استيراد نموذج Doctor

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        // الحصول على المستخدمين الذين دورهم 'doctor'
        $doctorUsers = User::where('role', 'doctor')->get();

        foreach ($doctorUsers as $user) {
            Doctor::create([
                'user_id' => $user->id,
                'name' => $faker->name(),
                'specialization' => $faker->randomElement(['Cardiology', 'Pediatrics', 'Dermatology', 'Neurology', 'Orthopedics', 'General Medicine']), // استخدام 'specialization' بدلاً من 'specialty'
                'license_number' => $faker->unique()->regexify('[A-Z]{2}[0-9]{5}'), // إضافة license_number
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
    }
}
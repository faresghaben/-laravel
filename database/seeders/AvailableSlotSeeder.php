<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Doctor;
use App\Models\AvailableSlot; // تأكد من استيراد نموذج AvailableSlot

class AvailableSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $doctors = Doctor::all();
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($doctors as $doctor) {
            // لكل طبيب، ننشئ بعض الفتحات المتاحة
            for ($i = 0; $i < 5; $i++) { // ننشئ 5 فتحات لكل طبيب
                $day = $faker->randomElement($daysOfWeek);
                $startTime = $faker->time('H:i:s', '17:00:00'); // وقت بدء عشوائي قبل الساعة 5 مساءً
                $endTime = date('H:i:s', strtotime($startTime) + 30 * 60); // 30 دقيقة بعد وقت البدء

                AvailableSlot::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => $faker->boolean(80), // استخدام 'is_available' بدلاً من 'is_booked'
                ]);
            }
        }
    }
}
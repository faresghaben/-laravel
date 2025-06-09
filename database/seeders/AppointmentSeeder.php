<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\AvailableSlot;
use App\Models\Appointment; // تأكد من استيراد نموذج Appointment

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $patients = Patient::all();
        $doctors = Doctor::all();

        // تأكد من وجود أطباء ومرضى لإنشاء المواعيد
        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->info('No patients or doctors found to create appointments.');
            return;
        }

        foreach ($patients as $patient) {
            // لكل مريض، ننشئ عدة مواعيد
            for ($i = 0; $i < 3; $i++) { // ننشئ 3 مواعيد لكل مريض
                $doctor = $doctors->random(); // اختيار طبيب عشوائي

                // البحث عن فتحة متاحة لهذا الطبيب
                // نستخدم 'is_available' بدلاً من 'is_booked'
                $availableSlot = AvailableSlot::where('doctor_id', $doctor->id)
                                                ->where('is_available', true) // استخدام 'is_available'
                                                ->inRandomOrder()
                                                ->first();

                if ($availableSlot) {
                    // إذا وجدت فتحة متاحة، نستخدمها لتحديد وقت الموعد
                    // بما أن الـ migration تستخدم datetime لـ start_time و end_time في Appointments
                    // سنقوم بإنشاء تاريخ ووقت كاملين بناءً على يوم الأسبوع والوقت من الفتحة المتاحة
                    $appointmentDate = $faker->dateTimeBetween('now', '+1 month'); // موعد خلال الشهر القادم
                    $appointmentStartTime = $appointmentDate->setTime(
                        (int) substr($availableSlot->start_time, 0, 2), // ساعة
                        (int) substr($availableSlot->start_time, 3, 2), // دقيقة
                        (int) substr($availableSlot->start_time, 6, 2)  // ثانية
                    );
                    $appointmentEndTime = $appointmentDate->setTime(
                        (int) substr($availableSlot->end_time, 0, 2),
                        (int) substr($availableSlot->end_time, 3, 2),
                        (int) substr($availableSlot->end_time, 6, 2)
                    );

                    Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'start_time' => $appointmentStartTime,
                        'end_time' => $appointmentEndTime,
                        'status' => $faker->randomElement(['scheduled', 'completed', 'canceled']),
                        'cancellation_reason' => ($faker->boolean(20) ? $faker->sentence : null), // 20% فرصة لإلغاء الموعد
                    ]);

                    // (اختياري) يمكن تحديث حالة 'is_available' في الفتحة المتاحة إلى false
                    // إذا كنت تريد أن تعتبر الفتحة محجوزة بعد إنشاء موعد عليها
                    // $availableSlot->update(['is_available' => false]);
                } else {
                    $this->command->warn("No available slots found for doctor ID: {$doctor->id}. Skipping appointment for patient ID: {$patient->id}");
                }
            }
        }
    }
}
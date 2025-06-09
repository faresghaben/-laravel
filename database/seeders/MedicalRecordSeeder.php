<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord; // تأكد من استيراد نموذج MedicalRecord

class MedicalRecordSeeder extends Seeder
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

        // تأكد من وجود مرضى وأطباء لإنشاء السجلات الطبية
        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->info('No patients or doctors found to create medical records.');
            return;
        }

        foreach ($patients as $patient) {
            // لكل مريض، ننشئ عدة سجلات طبية
            for ($i = 0; $i < 2; $i++) { // ننشئ سجلين لكل مريض
                $doctor = $doctors->random(); // اختيار طبيب عشوائي

                MedicalRecord::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'diagnosis' => $faker->sentence(5),
                    'treatment' => $faker->sentence(10),
                    'record_date' => $faker->date(),// استخدام 'record_data' بدلاً من 'record_date' و 'treatment' و 'notes'
                    // 'record_date' => $faker->date(), // <-- هذا العمود غير موجود في ملف الهجرة الخاص بك، لذا يجب إزالته
                    // 'treatment' => $faker->sentence(10), // <-- هذا العمود غير موجود في ملف الهجرة الخاص بك، لذا يجب إزالته
                    // 'notes' => $faker->paragraph(2), // <-- هذا العمود غير موجود في ملف الهجرة الخاص بك، لذا يجب إزالته
                ]);
            }
        }
    }
}
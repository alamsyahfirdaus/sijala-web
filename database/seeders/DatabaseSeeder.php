<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call([
            ProvincesSeeder::class,
            RegenciesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
            PuskesmasSeeder::class,
            UserSeeder::class,
            ElderlySeeder::class,
            EducationContentsSeeder::class,
            ElderlyFallRiskQuestionsSeeder::class,
            FamilyEmpowermentQuestionsSeeder::class,
            QaQuestionSeeder::class,
            CounselingSessionsSeeder::class,
            CounselingChatsSeeder::class,
            CounselingChatMessagesSeeder::class,
        ]);
    }
}

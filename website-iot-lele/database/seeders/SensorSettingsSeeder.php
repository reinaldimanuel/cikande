<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('sensor_settings')->insert([
            ['parameter' => 'ph', 'min_value' => 6.5, 'max_value' => 8.0],
            ['parameter' => 'temperature', 'min_value' => 25.0, 'max_value' => 30.0],
            ['parameter' => 'tds', 'min_value' => 200, 'max_value' => 500],
            ['parameter' => 'conductivity', 'min_value' => 500, 'max_value' => 1000],
            ['parameter' => 'salinity', 'min_value' => 0, 'max_value' => 1],
        ]);
    }
}
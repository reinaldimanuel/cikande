<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;
    
    protected $table = 'sensor_readings';
    protected $fillable = ['ph', 'temperature', 'tds', 'conductivity', 'salinity', 'created_at'];

    public function getStatus($value, $min, $max)
    {
        if ($value < $min) {
            return "Rendah";  // Low
        } elseif ($value > $max) {
            return "Tinggi";  // High
        } else {
            return "Normal";  // Optimal
        }
    }

    public function getPhStatusAttribute()
    {
        $settings = SensorSettings::first();
        return $this->getStatus($this->ph, $settings->min_ph, $settings->max_ph);
    }

    public function getTemperatureStatusAttribute()
    {
        $settings = SensorSettings::first();
        return $this->getStatus($this->temperature, $settings->min_temperature, $settings->max_temperature);
    }

    public function getTdsStatusAttribute()
    {
        $settings = SensorSettings::first();
        return $this->getStatus($this->tds, $settings->min_tds, $settings->max_tds);
    }

    public function getConductivityStatusAttribute()
    {
        $settings = SensorSettings::first();
        return $this->getStatus($this->conductivity, $settings->min_conductivity, $settings->max_conductivity);
    }

    public function getSalinityStatusAttribute()
    {
        $settings = SensorSettings::first();
        return $this->getStatus($this->salinity, $settings->min_salinity, $settings->max_salinity);
    }
}
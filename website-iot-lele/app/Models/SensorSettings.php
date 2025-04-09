<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorSettings extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_pond','min_ph', 'max_ph', 
        'min_temperature', 'max_temperature', 
        'min_tds', 'max_tds', 
        'min_conductivity', 'max_conductivity', 
        'min_salinity', 'max_salinity'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_readings';


    protected $fillable = [
        'id_pond',
        'ph',
        'temperature',
        'tds',
        'conductivity',
        'salinity',
    ];
    public $timestamps = true;    
}


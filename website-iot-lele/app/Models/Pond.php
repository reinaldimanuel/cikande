<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pond extends Model
{
    use HasFactory;

    protected $table = 'pond'; 
    protected $primaryKey = 'id_pond';
    public $incrementing = true;
    protected $keyType = 'string';

    protected $fillable = ['id_pond', 'name_pond', 'age_fish', 'total_fish','status_pond'];

    public $timestamps = true;

    public function latestReading()
    {
        return $this->hasOne(SensorReading::class, 'id_pond', 'id_pond')
                    ->latest('created_at'); // Fetch the latest sensor reading
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingHistory extends Model
{
    use HasFactory;

    protected $table = 'feeding_history'; 
    protected $fillable = ['id_pond',
        'jam_feeding',
        'menit_feeding',
        'total_food',
        'status',];
}
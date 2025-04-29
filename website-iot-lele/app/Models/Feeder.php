<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeder extends Model
{
    use HasFactory;

    protected $table = 'feeder'; // Table name
    protected $fillable = ['total_food' ,'feeder_status','id_pond'];
}
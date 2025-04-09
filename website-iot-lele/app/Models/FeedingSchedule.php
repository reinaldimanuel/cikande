<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingSchedule extends Model
{
    use HasFactory;

    protected $table = 'feeding_schedule'; // Table name

    protected $fillable = ['id_pond','feeding_time']; // Mass assignable fields

    public $timestamps = false; // No created_at or updated_at
}
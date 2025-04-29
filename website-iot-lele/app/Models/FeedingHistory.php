<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingHistory extends Model
{
    use HasFactory;

    protected $table = 'feeding_history'; // Table name
    protected $fillable = ['total_food','feeding_time', 'status'];
}
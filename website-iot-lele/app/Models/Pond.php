<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pond extends Model
{
    use HasFactory;

    protected $table = 'pond'; 
    protected $primaryKey = 'id_pond';
    public $incrementing = true;
    protected $keyType = 'string';

    protected $fillable = ['id_pond', 'name_pond', 'birth_fish', 'total_fish','status_pond','deact_reason'];

    public $timestamps = true;

    public function readings() {
        return $this->hasMany(SensorReading::class, 'id_pond', 'id_pond');
    }

    public function latestReading()
    {
        return $this->hasOne(SensorReading::class, 'id_pond', 'id_pond')
                    ->latest('created_at'); // Fetch the latest sensor reading
    }
    
    public function getTotalAgeInDaysAttribute()
    {
        if (!$this->birth_fish) {
            return null; // Atau bisa return 0 tergantung kebutuhan
        }

        $birthDate = Carbon::parse($this->birth_fish);
        $now = Carbon::now();

        return $birthDate->diffInDays($now);
    }

    /**
     * Format umur menjadi lebih mudah dibaca
     */
    public function getFormattedAgeAttribute()
    {
        $totalDays = $this->total_age_in_days;

        if (is_null($totalDays)) {
            return '-';
        }

        return $this->formatAge($totalDays);
    }

    /**
     * Helper untuk format umur
     */
    protected function formatAge($totalDays)
    {
        $years = floor($totalDays / 365);
        $remainingDays = $totalDays % 365;

        $months = floor($remainingDays / 30);
        $remainingDays = $remainingDays % 30;

        $weeks = floor($remainingDays / 7);
        $days = $remainingDays % 7;

        if ($years > 0) {
            return $years . ' tahun' . ($months > 0 ? ' ' . $months . ' bulan' : '');
        } 
        elseif ($months > 0) {
            $weekPart = $weeks > 0 ? ' ' . $weeks . ' minggu' : '';
            return $months . ' bulan' . $weekPart;
        }
        elseif ($weeks > 0) {
            return $weeks . ' minggu' . ($days > 0 ? ' ' . $days . ' hari' : '');
        }
        else {
            return $days . ' hari';
        }
    }
}
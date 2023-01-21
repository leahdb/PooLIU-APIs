<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'location',
        'campus',
        'date',
        'time'
    ];

    public function driver()
    {
        return $this->belongsTo(DriverTrip::class);
    }

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'trip_passengers');
    }

    public function scopeDate($query, $date)
    {
        return $query->whereDate('trips.date', '>=', Carbon::parse($date)->toDateTimeString());
    }
}

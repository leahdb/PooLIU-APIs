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
        'time',
        'ride_type',
        'seats',
        'is_going'
    ];

    // public function driver()
    // {
    //     return $this->belongsTo(DriverTrip::class);
    // }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'trip_passengers');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_riders');
    }
    public function riderRequests()
    {
        return $this->hasMany(RiderRequest::class);
    }
}

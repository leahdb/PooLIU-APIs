<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver_Trips extends Model
{
    use HasFactory;

    protected $table = 'driver_trips';
    public $timestamps = true;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

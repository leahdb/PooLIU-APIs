<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip_Riders extends Model
{
    use HasFactory;

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

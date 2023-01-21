<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider_Requests extends Model
{
    use HasFactory;

    protected $table = 'rider_requests';
    public $timestamps = true;

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

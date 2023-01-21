<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'location' => $this->location,
            'is_going' => $this->is_going,
            'campus' => $this->campus,
            'date' => $this->date,
            'time' => $this->time,
            'ride_type' => $this->ride_type,
            'seats' => $this->seats,
            'is_going' => $this->is_going,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at            
        ];
    }
}

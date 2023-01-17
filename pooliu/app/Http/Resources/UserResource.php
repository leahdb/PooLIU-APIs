<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'LIU_ID' => $this->LIU_ID,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'verification_num' => $this->verification_num,
            'verification_status' => $this->verification_status,
            'password' => $this->password,
            'phone_num' => $this->phone_num,
            'profile_pic' => $this->profile_pic,
            'is_LIU' => $this->is_LIU,
            'gender' => $this->gender,
            'score' => $this->score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
            
        ];
    }
}

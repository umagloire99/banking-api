<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>  $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'dob' => $this->dob,
            'address' => $this->address
        ];
    }
}

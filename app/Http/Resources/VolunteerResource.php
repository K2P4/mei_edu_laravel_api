<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'phone' => $this->phone,
            'position' => $this->position,
            'dob' => $this->dob ? Carbon::parse($this->dob)->format('Y-m-d') : null, 
            'team' => $this->team,
            'batch' => $this->batch,
            'department' => $this->department,
            'gender' => $this->gender,
            'volunteer_id' => $this->volunteer_id,
            'rating' => $this->rating,
        ];
    }
}

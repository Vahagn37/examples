<?php

namespace App\Http\Resources\Appointment;

use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Service\ServiceResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'barber' => new EmployeeResource($this->barber),
            'service' => new ServiceResource($this->service),
            'type' => $this->type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'start' => Carbon::parse($this->start)->format('Y-m-d H:i'),
            'end' => Carbon::parse($this->end)->format('Y-m-d H:i'),
            'duration' => $this->duration,
        ];
    }
}


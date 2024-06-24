<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Appointment;
use App\Traits\OnlyAdminAccess;

class UpdateRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected string $resource = Appointment::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'barber_id' => 'required|integer|exists:employees,id',
            'service_id' => 'required|integer|exists:services,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'start' => 'required|date_format:Y-m-d H:i',
            'end' => 'required|date_format:Y-m-d H:i',
            'duration' => 'required|integer',
        ];

        return array_merge(
            parent::rules(),
            $rules
        );
    }
}

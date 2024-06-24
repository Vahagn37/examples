<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Employee;
use App\Traits\OnlyAdminAccess;

class StoreRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected string $resource = Employee::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|string',
            'image_name' => 'required',
            'is_barber' => 'nullable|boolean',
        ];

        return array_merge(
            parent::rules(),
            $rules
        );
    }
}

<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Service;
use App\Traits\OnlyAdminAccess;

class UpdateRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected string $resource = Service::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'price' => 'nullable|integer',
            'duration' => 'required|integer',
            'image_name' => 'nullable',
        ];

        return array_merge(
            parent::rules(),
            $rules
        );
    }
}

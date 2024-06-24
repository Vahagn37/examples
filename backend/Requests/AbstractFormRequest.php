<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbstractFormRequest extends FormRequest
{
    protected bool $pageable = false;
    protected bool $filterable = false;
    protected string $resource;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            $this->pageable ? $this->pageableRules() : [],
            $this->filterable ? $this->filterableRules() : [],
        );
    }

    /**
     * @return string[]
     */
    protected function pageableRules(): array
    {
        return [
            'page' => 'required|integer',
            'pagination' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    protected function filterableRules(): array
    {
        return [
            'filter' => 'nullable|array:'. implode(',', $this->getFilterableFields())
        ];
    }

    /**
     * @return array
     */
    protected function getFilterableFields(): array
    {
        if (method_exists($this->resource, 'getFilterableAttributes')) {
            return $this->resource::getFilterableAttributes();
        }
        return [];
    }

}

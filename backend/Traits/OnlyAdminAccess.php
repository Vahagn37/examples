<?php

namespace App\Traits;


trait OnlyAdminAccess
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }
}

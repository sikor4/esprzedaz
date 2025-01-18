<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PetStatus;

class StorePetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $statusValues = implode(',', PetStatus::values());

        return [
            'name'   => 'required|string|max:255',
            'status' => 'required|string|in:' . $statusValues
        ];
    }

    public function messages()
    {
        $allStatuses = implode(', ', PetStatus::values());

        return [
            'name.required'   => 'Nazwa jest wymagana',
            'status.required' => 'Status jest wymagany',
            'status.in'       => 'Nieprawidłowy status. Dostępne statusy to ' . $allStatuses
        ];
    }
}

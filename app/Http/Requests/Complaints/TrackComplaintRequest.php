<?php

namespace App\Http\Requests\Complaints;

use Illuminate\Foundation\Http\FormRequest;

class TrackComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reference_number' => strtoupper(
                trim((string) $this->input('reference_number'))
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'reference_number' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'reference_number.required' =>
                'يرجى إدخال رقم التتبع.',

            'reference_number.string' =>
                'رقم التتبع غير صالح.',

            'reference_number.max' =>
                'رقم التتبع غير صالح.',
        ];
    }
}

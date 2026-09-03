<?php

namespace App\Http\Requests\Complaints;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'required',
                'string',
                'max:5000',
            ],

            'government_entity_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:government_entities,id',
            ],

            'location' => [
                'sometimes',
                'required',
                'array',
            ],

            'location.address' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],

            'location.details' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' =>
                'يرجى تحديد نوع الشكوى.',

            'type.string' =>
                'نوع الشكوى غير صالح.',

            'type.max' =>
                'نوع الشكوى يجب ألا يتجاوز 255 حرفًا.',

            'description.required' =>
                'يرجى إدخال وصف الشكوى.',

            'description.string' =>
                'وصف الشكوى غير صالح.',

            'description.max' =>
                'وصف الشكوى طويل جدًا.',

            'government_entity_id.required' =>
                'يرجى اختيار الجهة الحكومية.',

            'government_entity_id.exists' =>
                'الجهة الحكومية المختارة غير موجودة.',

            'location.array' =>
                'بيانات الموقع غير صالحة.',

            'location.address.max' =>
                'عنوان الموقع طويل جدًا.',

            'location.details.max' =>
                'تفاصيل الموقع طويلة جدًا.',
        ];
    }
}

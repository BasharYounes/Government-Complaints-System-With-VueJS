<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',

                Rule::unique('users', 'email')
                    ->ignore($this->user()?->id),
            ],

            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:4096',
            ],
        ];
    }


    public function messages(): array
    {
        return [

            'name.required' =>
                'يجب إدخال الاسم.',

            'name.max' =>
                'الاسم يجب ألا يتجاوز 255 حرفًا.',

            'email.required' =>
                'يجب إدخال البريد الإلكتروني.',

            'email.email' =>
                'صيغة البريد الإلكتروني غير صحيحة.',

            'email.unique' =>
                'البريد الإلكتروني مستخدم مسبقًا.',

            'phone.max' =>
                'رقم الهاتف طويل جدًا.',

            'image.image' =>
                'الملف المختار يجب أن يكون صورة.',

            'image.mimes' =>
                'صيغة الصورة غير مدعومة.',

            'image.max' =>
                'حجم الصورة يجب ألا يتجاوز 4 ميغابايت.',
        ];
    }
}

<?php

namespace App\Http\Requests\Attachments;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'file' => [
                'sometimes',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:10240',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'يجب إرفاق ملف.',
            'file.file' => 'يجب أن يكون الملف المرفق ملفًا صالحًا.',
            'file.max' => 'حجم الملف المرفق يجب ألا يتجاوز 10 ميجابايت.',
            'file.mimes' => 'نوع الملف غير مدعوم. الملفات المسموحة: PDF، JPG، PNG، DOC، DOCX.',
            'complaint_id.required' => 'معرف الشكوى مطلوب.',
            'complaint_id.exists' => 'الشكوى المحددة غير موجودة.',
        ];
    }
}

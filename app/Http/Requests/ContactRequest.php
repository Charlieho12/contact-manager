<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Backpack typically ensures auth via middleware; keep true here.
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('contact'); // Backpack route param name

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:contacts,email' . ($id ? ",".$id : '')],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}

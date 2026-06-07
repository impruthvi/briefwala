<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscriber;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class StoreSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', 'unique:subscribers,email'],
            'niche' => ['required', Rule::in(['Tech', 'Comedy', 'Finance', 'Fitness', 'Food', 'Lifestyle', 'Gaming', 'Education'])],
            'platform' => ['required', Rule::in(['YouTube', 'Instagram', 'Both'])],
            'language' => ['required', Rule::in(['Hindi', 'English', 'Hinglish', 'Tamil', 'Telugu'])],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already subscribed.',
        ];
    }
}

<?php

namespace App\Http\Requests\Tag;

use Carbon\Exceptions\InvalidPeriodDateException;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Type\Integer;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'uid' => ['required', 'string'],
        ];
    }

    public function uid() : string
    {
        return $this->input('uid');
    }
}
<?php

namespace App\Http\Requests\User;

use Carbon\Exceptions\InvalidPeriodDateException;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Type\Integer;

class ReserveRequest extends FormRequest
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
            'start' => ['required', 'string'],
            'end' => ['required', 'string']
        ];
    }

    public function start() : DateTime
    {
        return new DateTime($this->input('start'));
    }

    public function end() : DateTime
    {
        return new DateTime($this->input('end'));
    }
}
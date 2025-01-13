<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'birth_date' => ['required', 'string', 'date', 'before:today'],
            'has_comorbidity' => ['required', 'boolean'],

            'dose_1' => ['required', 'array'],
            'dose_1.vaccine_id' => ['nullable', 'integer', 'exists:vaccines,id'],
            'dose_1.vaccine_lot_id' => ['nullable', 'integer'],
            'dose_1.lot_id' => ['nullable', 'string', 'min:1', 'max:255'],
            'dose_1.expiration_date' => ['nullable', 'date'],
            'dose_1.applied_at' => ['nullable', 'date', 'before_or_equal:today'],

            'dose_2' => ['required', 'array'],
            'dose_2.vaccine_id' => ['nullable', 'integer', 'exists:vaccines,id'],
            'dose_2.vaccine_lot_id' => ['nullable', 'integer'],
            'dose_2.lot_id' => ['nullable', 'string', 'min:1', 'max:255'],
            'dose_2.expiration_date' => ['nullable', 'date'],
            'dose_2.applied_at' => ['nullable', 'date', 'before_or_equal:today'],

            'dose_3' => ['required', 'array'],
            'dose_3.vaccine_id' => ['nullable', 'integer', 'exists:vaccines,id'],
            'dose_3.vaccine_lot_id' => ['nullable', 'integer'],
            'dose_3.lot_id' => ['nullable', 'string', 'min:1', 'max:255'],
            'dose_3.expiration_date' => ['nullable', 'date'],
            'dose_3.applied_at' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        foreach (['dose_1', 'dose_2', 'dose_3'] as $dose) {
            $validator->sometimes("$dose.lot_id", 'required', function ($input) use ($dose) {
                return !is_null(data_get($input, "$dose.vaccine_id"));
            });

            $validator->sometimes("$dose.expiration_date", 'required', function ($input) use ($dose) {
                return !is_null(data_get($input, "$dose.vaccine_id"));
            });

            $validator->sometimes("$dose.applied_at", 'required', function ($input) use ($dose) {
                return !is_null(data_get($input, "$dose.vaccine_id"));
            });
        }
    }
}

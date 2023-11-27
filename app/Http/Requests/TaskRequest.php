<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'parent_id' => ['integer'],
            'status' => ['string', Rule::in(TaskStatus::toArray())],
            'priority' => ['integer', 'min:1', 'max:5'],
            'title' => ['required', 'unique:tasks', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ];
    }
}

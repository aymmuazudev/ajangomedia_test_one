<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('user');

        return $target instanceof User
            ? $this->user()->can('update', $target)
            : $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        $target = $this->route('user');

        $emailRule = Rule::unique(User::class);
        if ($target instanceof User) {
            $emailRule->ignore($target);
        }

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', $emailRule],
            'role' => ['required', 'string', Rule::in(array_keys(User::roleOptionsFor($this->user())))],
        ];
    }
}

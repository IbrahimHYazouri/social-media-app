<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class InviteUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function getInvitee(): User
    {
        return User::where('email', $this->validated('email'))->firstOrFail();
    }

    public function getExistingPivot(): ?GroupUser
    {
        $invitee = $this->getInvitee();
        $group = $this->route('group');

        return GroupUser::where([
            ['group_id', $group->id],
            ['user_id', $invitee->id],
        ])->first();
    }
}

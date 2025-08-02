<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\GroupUserStatusEnum;
use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;

final class ApproveJoinRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'action' => ['required', 'string', 'in:approve,reject'],
        ];
    }

    public function getGroupUser(): GroupUser
    {
        $data = $this->validated();

        return GroupUser::where('group_id', $this->route('group')->id)
            ->where('user_id', $data['user_id'])
            ->where('status', GroupUserStatusEnum::PENDING)
            ->firstOrFail();
    }
}

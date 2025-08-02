<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class JoinGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $group = $this->route('group');

        $already = GroupUser::where([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ])->exists();

        return $user && ! $already;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}

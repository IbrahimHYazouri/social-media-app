<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\TotalFileSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class StorePostRequest extends FormRequest
{
    public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'doc', 'docx', 'pdf', 'csv', 'xls', 'xlsx',
    ];

    /**
     * Maximum file size in bytes (10MB per file)
     */
    public static int $maxFileSize = 10 * 1024 * 1024;

    /**
     * Maximum total size for all files (150B)
     */
    public static int $maxTotalSize = 150 * 1024 * 1024;

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
            'body' => ['nullable', 'string', 'max:1024'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'attachments' => ['sometimes', 'array', 'max:15', new TotalFileSize(self::$maxTotalSize)],
            'attachments.*' => ['file',
                File::types(self::$extensions)->max(self::$maxFileSize / 1024),
            ],
            'group_id' => ['nullable', 'integer', 'exists:groups,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.mimes' => 'Invalid file type.',
        ];
    }
}

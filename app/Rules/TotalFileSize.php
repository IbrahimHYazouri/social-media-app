<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class TotalFileSize implements ValidationRule
{
    public function __construct(private int $maxBytes = 1073741824) // 1GB
    {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $total = collect($value)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->sum(fn (UploadedFile $file) => $file->getSize());

        if ($total > $this->maxBytes) {
            $fail("The total size of all files in `$attribute` must not exceed ".$this->formatBytes($this->maxBytes).'.');
        }
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), $precision).' '.$units[$pow];
    }
}

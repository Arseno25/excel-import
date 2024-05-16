<?php

namespace Arseno25\ExcelImport;

use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Arseno25\ExcelImport\Concerns\HasImportData;
use Arseno25\ExcelImport\Concerns\HasValidation;

class ExcelImport implements ToCollection, WithHeadingRow
{
    use HasImportData, HasValidation;

    public bool $hasDuplicate = false;

    public function __construct(
        protected string $model,
        protected array $attributes = [],
    ) {

    }
}
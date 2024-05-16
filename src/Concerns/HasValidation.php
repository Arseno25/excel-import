<?php

namespace Arseno25\ExcelImport\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

trait HasValidation
{
    public function collection(Collection $collection): void
    {
        $duplicates = [];
        $modelInstance = new $this->model;
        $existingRecords = $modelInstance->newQuery()->get();
        $firstOccurrence = [];

        foreach ($collection as $index => $row) {
            $rowData = $row->toArray();
            if (config('excel_import.validate_duplicates')) {
                $duplicate = $existingRecords->first(function ($record) use ($rowData, $modelInstance) {
                    foreach ($modelInstance->getFillable() as $attribute) {
                        if (isset($rowData[$attribute]) && $record->$attribute == $rowData[$attribute]) {
                            return true;
                        }
                    }
                    return false;
                });

                if ($duplicate || in_array($rowData, $firstOccurrence)) {
                    $duplicates[] = 'Row ' . ($index + 1);
                    continue;
                }
            }
            try {
                $newInstance = clone $modelInstance;
                $newInstance::create($rowData);
                $firstOccurrence[] = $rowData;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    // Handle duplicate entry error
                    $duplicates[] = 'Table Data Row ' . ($index + 1);
                } else {
                    throw $e;
                }
            }
        }
        if (!empty($duplicates) && config('excel_import.validate_duplicates')) {
            Notification::make()
                ->title('Duplicate Data')
                ->body('The following records already exist in the database: ' . implode(', ', $duplicates))
                ->warning()
                ->send();
            $this->hasDuplicate = true;
        }
    }
}
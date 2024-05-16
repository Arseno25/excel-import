<?php

namespace Arseno25\ExcelImport\Concerns;

use Closure;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Excel;

trait HasImportData
{
    private function importData(): Closure
    {
        return function (array $data, $livewire): bool {
            $importObject = new $this->importClass($livewire->getModel(), ...$this->importClassAttributes);
            $excel = app(Excel::class);
            try {
                $excel->import($importObject, $data['upload']);

                if ($importObject->hasDuplicate) {
                    return false;
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                Notification::make()
                    ->title('Validation Error')
                    ->body('There was an error validating the data. Please check the data and try again.')
                    ->warning()
                    ->send();

                return false;
            }

            Notification::make()
                ->title('Data Imported')
                ->body('Data imported successfully.')
                ->success()
                ->send();

            return true;
        };
    }
}

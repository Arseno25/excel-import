<?php

namespace Arseno25\ExcelImport;

use Exception;
use Filament\Actions\Action;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Excel;

class ExcelImportAction extends Action
{
    protected string $importClass = DefaultExcelImport::class;
    protected array $importClassAttributes = [];
    protected ?string $disk = null;

    public function use( string $class=null, ...$attributes): static
    {
        $this->importClass = $class ?: DefaultExcelImport::class;
        $this->importClassAttributes = $attributes;

        return $this;
    }

    protected function getDisk()
    {
        return $this->disk ?: config('filesystems.default');
    }


    public static function getDefaultName(): ?string
    {
        return 'Import Excel';
    }

    /**
     * @throws Exception
     */
    public function action(Closure | string | null $action ): static
    {
        if ( $action !== 'importData' ) {
            $exception = new Exception('You are only allowed to use the "action" method for this plugin.');
            Notification::make()
               ->title('Invalid Action')
                ->body( $exception->getMessage())
                ->warning()
                ->send();

            throw $exception;
        }

        $this->action = $this->importData();

        return $this;
    }

    protected function getDefaultForm(): array
    {
        return [
            Section::make()
                ->description(fn ($livewire) => str($livewire->getTable()->getPluralModelLabel())->title(). ' ' . __('Data'))
            ->schema([
                FileUpload::make('upload')
                    ->label('Upload File')
                    ->default(1)
                    ->disk($this->getDisk())
                    ->columns()
                    ->required(),
            ]),
        ];
    }

    protected function getDefaultColumn(): array
    {
        return [
            'import' => 'Import Excel',
        ];
    }

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-document-arrow-down')
            ->color('warning')
            ->form($this->getDefaultForm())
            ->modalIcon('heroicon-o-document-arrow-down')
            ->color('success')
            ->modalWidth('md')
            ->modalAlignment('center')
            ->modalHeading(fn ($livewire) => __('Import Data'))
            ->modalDescription(__('Import data into database from excel file'))
            ->modalFooterActionsAlignment('right')
            ->closeModalByClickingAway(false)
            ->action('importData');
    }

    /**
     * import data from excel file
     *
     * @return Closure Returns true if the data was imported successfully, false otherwise
     */
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
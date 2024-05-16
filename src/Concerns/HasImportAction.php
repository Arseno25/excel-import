<?php

namespace Arseno25\ExcelImport\Concerns;

use Arseno25\ExcelImport\ExcelImport;
use Closure;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;

trait HasImportAction
{
    use HasImportData;

    protected string $importClass = ExcelImport::class;
    protected array $importClassAttributes = [];
    protected ?string $disk = null;

    public function use(string $class = null, ...$attributes): static
    {
        $this->importClass = $class ?: ExcelImport::class;
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
    public function action(Closure | string | null $action): static
    {
        if ($action !== 'importData') {
            $exception = new Exception('You are only allowed to use the "action" method for this plugin.');
            Notification::make()
                ->title('Invalid Action')
                ->body($exception->getMessage())
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
                ->description(fn ($livewire) => str($livewire->getTable()->getPluralModelLabel())->title() . ' ' . __('Data'))
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
}
<?php

namespace Arseno25\ExcelImport\Action;

use Arseno25\ExcelImport\Concerns\HasImportAction;
use Filament\Actions\Action;

class ImportAction extends Action
{
    use HasImportAction;

    /**
     * @throws \Exception
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
}
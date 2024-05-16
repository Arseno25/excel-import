<?php

namespace Arseno25\ExcelImport\Facades;

use Illuminate\Support\Facades\Facade;

class ExcelImportAction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Arseno25\ExcelImport\Action\ImportAction::class;
    }
}
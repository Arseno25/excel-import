<?php

namespace Arseno25\ExcelImport\Commands;

use Illuminate\Console\Command;

class ExcelImportActionCommand extends Command
{
    public $signature = 'excel-import';
    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }

}
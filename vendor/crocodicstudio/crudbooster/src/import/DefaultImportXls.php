<?php

namespace crocodicstudio\crudbooster\import;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DefaultImportXls implements ToArray, WithChunkReading
{
    public function array(array $rows)
    {
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ValueSetConditionCodeKeluhanUtamaSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/valueset_condition_code_keluhanutama.csv';
        $this->timestamps = false;
        $this->delimiter = ',';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}

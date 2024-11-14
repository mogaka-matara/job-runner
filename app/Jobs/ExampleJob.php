<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class ExampleJob
{
    public function handle($param1, $param2)
    {

        Log::info("Running ExampleJob with parameters: $param1, $param2");

        sleep(2);
    }
}

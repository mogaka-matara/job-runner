<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobRunnerController extends Controller
{
    public function startJob()
    {
        runBackgroundJob('App\Jobs\ExampleJob', 'executeTask', ['param1', 'param2'], 3, 5);
        return 'Job has been dispatched!';
    }
}

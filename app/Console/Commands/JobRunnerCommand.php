<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobRunner;

class JobRunnerCommand extends Command
{
    protected $signature = 'job:runner
                            {class : The fully qualified class name of the job}
                            {method : The method to call on the job class}
                            {--params= : JSON-encoded parameters to pass to the method}
                            {--retries=1 : Number of retries if the job fails}
                            {--delay=0 : Delay (in seconds) between each retry}';

    protected $description = 'Run a job in the background with optional retries and delay';

    protected JobRunner $jobRunner;

    public function __construct(JobRunner $jobRunner)
    {
        parent::__construct();
        $this->jobRunner = $jobRunner;
    }

    public function handle()
    {
        $class = $this->argument('class');
        $method = $this->argument('method');
        $params = json_decode($this->option('params'), true) ?: [];
        $retries = (int) $this->option('retries');
        $delay = (int) $this->option('delay');

        if (!class_exists($class)) {
            $this->error("Class $class does not exist.");
            return;
        }

        if (!method_exists($class, $method)) {
            $this->error("Method $method does not exist in class $class.");
            return;
        }

        $this->jobRunner->run($class, $method, $params, $retries, $delay);

        $this->info("Job for class $class::$method has been dispatched.");
    }
}

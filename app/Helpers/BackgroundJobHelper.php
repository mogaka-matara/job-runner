<?php

use App\Models\JobLog;
use Illuminate\Support\Facades\Log;

function runBackgroundJob($class, $method, $params = [], $priority = 0, $delay = 0, $maxRetries = 3)
{
    try {
        $jobLog = JobLog::create([
            'class' => $class,
            'method' => $method,
            'params' => json_encode($params),
            'status' => 'running',
            'priority' => $priority,
            'delay' => $delay,
            'retries' => 0,
        ]);

        $retries = 0;
        while ($retries < $maxRetries) {
            try {
                $jobInstance = new $class;

                if (method_exists($jobInstance, $method)) {
                    call_user_func_array([$jobInstance, $method], $params);

                    $jobLog->status = 'completed';
                    $jobLog->save();
                    return;
                } else {
                    throw new \Exception("Method $method does not exist in class $class.");
                }
            } catch (\Exception $e) {
                $retries++;
                $jobLog->retries = $retries;
                $jobLog->status = 'failed';
                $jobLog->error_message = $e->getMessage();
                $jobLog->save();

                Log::error("Job failed on retry $retries: " . $e->getMessage());

                if ($retries >= $maxRetries) {
                    $jobLog->status = 'failed';
                    $jobLog->save();
                    return;
                }

                sleep(5);
            }
        }
    } catch (\Exception $e) {
        Log::error("Unexpected error: " . $e->getMessage());
    }
}

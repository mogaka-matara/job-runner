<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class JobRunner
{
    /**
     * Run the job class in the background.
     *
     * @param string $class
     * @param string $method
     * @param array $params
     * @param int $retries
     * @param int $delay
     */
    public function run(string $class, string $method, array $params = [], int $retries = 1, int $delay = 0)
    {
        $retryCount = 0;
        $success = false;

        Log::info("Job {$class}@{$method} is starting with parameters: " . json_encode($params));

        while ($retryCount < $retries && !$success) {
            try {
                $job = new $class();

                $result = call_user_func_array([$job, $method], $params);

                if ($result) {
                    Log::info("Job {$class}@{$method} completed successfully.");
                    $success = true;
                }
            } catch (Exception $e) {
                $retryCount++;
                Log::channel('background_jobs_errors')->error("Job {$class}@{$method} failed. Retry {$retryCount}/{$retries}. Error: {$e->getMessage()}");

                if ($retryCount < $retries) {
                    sleep($delay);
                }
            }
        }

        if (!$success) {
            Log::channel('background_jobs_errors')->error("Job {$class}@{$method} failed after {$retries} retries.");
        }
    }
}

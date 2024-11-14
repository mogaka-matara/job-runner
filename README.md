Laravel Custom Background Job Runner Documentation

1. Introduction
   This project introduces a custom background job system in Laravel, independent of Laravel's default queue system. The solution allows asynchronous job processing, error logging, job retries, prioritization, and a web-based dashboard for monitoring job statuses. This document provides detailed instructions to configure and utilize this custom job runner in a Laravel application.

2. Project Requirements
   Laravel Framework: A Laravel application with a configured database.
   Composer: Dependency management.
   Logging: Custom logging configuration for job statuses.
   Optional: Laravel Breeze for authentication and a web-based job monitoring dashboard.

3. System Design and Architecture
   The project comprises a Job Runner class that handles job execution, logging, error handling, retries, delays, and priorities. Jobs are queued and executed in the background using a helper function that simplifies job dispatching across the application. An optional web-based dashboard allows administrators to monitor job statuses, view logs, and handle job retries or cancellations.

4. Setup and Installation
   Clone the Laravel Application: Ensure you have a Laravel project set up and ready to customize.
   Install Dependencies:
   composer install
   Configure the Database: Set up your .env file with database credentials.

5. Core Components
   Job Runner
   Path: app/Jobs/JobRunner.php
   The JobRunner class manages the execution of jobs, including retry attempts, delay configurations, and priority handling. It also logs each job's status and captures any errors encountered during execution.



Helper Function
Path: app/Helpers/jobRunnerHelper.php
This function simplifies the process of dispatching background jobs across the application.
Add this helper function to Composer's autoload:
Path: composer.json
"autoload": {
"files": [
"app/Helpers/jobRunnerHelper.php"
]
}

Then, run:
composer dump-autoload


6. Advanced Features
   Retry Mechanism
   The JobRunner class includes retry attempts. The job automatically retries up to the configured attempt count if it fails.
   Error Handling
   Errors are captured and logged in the background_jobs.log file, ensuring admins can trace issues with specific jobs.
   Delays and Priorities
   Delays: Configure delays in seconds for delayed job execution.
   Priorities: Set priority levels; higher-priority jobs are executed before lower-priority ones.
7. Web-Based Job Dashboard
   Setting Up Laravel Breeze
   Install Laravel Breeze for authentication:
   composer require laravel/breeze --dev
   php artisan breeze:install
   npm install && npm run dev
   php artisan migrate

Dashboard Controller
Path: app/Http/Controllers/JobDashboardController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobDashboardController extends Controller
{
    public function index()
    {
        $logs = file(storage_path('logs/background_jobs.log'));

        return view('dashboard', ['logs' => $logs]);
    }
}


Dashboard Route
Path: routes/web.php
use App\Http\Controllers\JobDashboardController;

Route::get('/dashboard', [JobDashboardController::class, 'index'])->middleware(['auth']);


8. Using the Job Runner
To run a job, use the helper function from any part of the project:
runBackgroundJob('App\Jobs\ExampleJob', 'handle', [], 3, 1, 10); // Example with priority 1 and 10-second delay



Running Jobs via Routes
You can also trigger jobs via routes.
use Illuminate\Support\Facades\Route;

Route::get('/run-job', function () {
    runBackgroundJob('App\Jobs\ExampleJob', 'handle');
    return 'Job is running in the background!';
});





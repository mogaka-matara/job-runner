<?php

namespace App\Http\Controllers;

use App\Models\JobLog;
use Illuminate\Http\Request;

class JobDashboardController extends Controller
{


    public function index()
    {
        $jobLogs = JobLog::latest()->paginate(10);
        return view('admin.job-dashboard.index', compact('jobLogs'));
    }

    public function cancelJob($id)
    {
        $jobLog = JobLog::findOrFail($id);
        $jobLog->status = 'cancelled';
        $jobLog->save();

        return redirect()->route('job-dashboard.index')->with('status', 'Job cancelled successfully.');
    }

}

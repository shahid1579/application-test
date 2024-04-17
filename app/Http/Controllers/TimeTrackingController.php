<?php

namespace App\Http\Controllers;

use App\Models\TimeTracking;
use Illuminate\Http\Request;

class TimeTrackingController extends Controller
{
    public function startTimer() {
        // Start the timer
        $timer = TimeTracking::create([
            'project_id' => request()->project_id,
            'start_time' => now(),
            'end_time' => null,
        ]);

        // Return the created timer
        return new TimeTracking([$timer]);
    }

    public function stopTimer($id) {
        // Find the timer
        $timer = TimeTracking::findOrFail($id);

        // Stop the timer
        $timer->update([
            'end_time' => now(),
        ]);

        // Return the updated timer
        return new TimeTracking([$timer]);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeTracking;
use App\Mail\TimeTrackingSummary;

class SendTimeTrackingSummaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-time-tracking-summaries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $timeTracking = TimeTracking::where('user_id', $user->id)->whereBetween('start_time',[now()->startOfWeek(),now()->endOfWeek()])->get();

            Mail::to($user->email)->send(new TimeTrackingSummary($users,$timeTracking));

        }
    }
}

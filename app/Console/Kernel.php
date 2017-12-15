<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Questionnaire;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $data = DB::table('questionnaires')->get();
            if($data != null){
                foreach ($data as $key => $val){
                    if($val->recovery_at != null){
                        $today_at = Carbon::now();
                        if($val->recovery_at <= $today_at){
                            $status = 2;
                            $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
                        }
                        else{
                            $status = 1;
                            $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
                        }
                    }
                }
            }


        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

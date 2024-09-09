<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\EmployeePermitNotify::class,
        Commands\ProjectContractExpiryNotify::class,
        Commands\EmployeeLetterMissingEndorsedDocumentNotify::class,
        Commands\GeneratePayroll::class,
        Commands\GenerateAttendence::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('permit:cron')
                ->daily();
        $schedule->command('contract:cron')
                ->daily();
        $schedule->command('missing_endorsed_document:cron')
                ->daily();
        $schedule->command('generate_payroll:cron')->daily();
        $schedule->command('generate_attendance:cron')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}

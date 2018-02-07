<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\HighscoresCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ollyxpic:highscores')->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['magic'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['sword'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['axe'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['club'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['distance'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['shielding'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['achievements'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['loyalty'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['fist'])->dailyAt('04:00');
        $schedule->command('ollyxpic:highscores', ['fishing'])->dailyAt('04:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

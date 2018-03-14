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
        Commands\HighscoresCommand::class,
        Commands\HighscoresSkillsCommand::class,
        Commands\PlayersOnlineCommand::class,
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
        $schedule->command('ollyxpic:skills', ['magic'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['sword'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['axe'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['club'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['distance'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['shielding'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['achievements'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['loyalty'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['fist'])->dailyAt('04:00');
        $schedule->command('ollyxpic:skills', ['fishing'])->dailyAt('04:00');
        $schedule->command('ollyxpic:online')->everyFiveMinutes();
        $schedule->command('ollyxpic:online', ['enemies'])->everyFiveMinutes();
    }

    /**
     * Register the Closure based cgommands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

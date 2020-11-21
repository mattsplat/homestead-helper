<?php

namespace App\Commands;

use App\Lib\Config;
use App\Lib\DomainShell;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class RunComposer extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:composer {domain}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $shell = DomainShell($this->argument('domain'));
        $process = $shell->run([
            'cd '
        ]);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}

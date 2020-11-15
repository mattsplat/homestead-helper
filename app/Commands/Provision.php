<?php

namespace App\Commands;

use App\Lib\Config;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

class Provision extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'provision';

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
        $config = Config::get();

        // halt vagrant box
        $process = Process::fromShellCommandline('vagrant halt');
        $process->setWorkingDirectory($config['path']);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo $buffer;
            }
        });

        // restart with provision flag
        if($process->isSuccessful()) {
            $this->info('Vagrant Halted Successfully');
            $provision  = Process::fromShellCommandline('vagrant up --provision');
            $provision->setWorkingDirectory($config['path']);
            $provision->setTimeout(3600);
            $provision->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo $buffer;
                }
            });

            if($provision->isSuccessful()) {
                $this->info('Provision ran');
            } else {
                $this->error('provision failed '. $provision->getOutput());
            }

        } else {
            $this->error('Halt failed '. $process->getOutput());
        }
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

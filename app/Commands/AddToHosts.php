<?php

namespace App\Commands;

use App\Lib\Config;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class AddToHosts extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'add:host {domain}';

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
        $config =  Config::get();
        $yaml = Yaml::parseFile($config['path'].'/Homestead.yaml');
        $domain = $this->argument('domain');
        if(!$domain) {
            $this->error('Domain is required');
            return;
        }


        $domain_entry = $yaml['ip']. ' '. $domain;
        $process = Process::fromShellCommandline(
            'sudo -- sh -c "echo '.$domain_entry .' >> '. $config['hosts_file'] . '"'
        );

        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo $buffer;
            }
        });

        if($process->isSuccessful()) {
            $this->info('Successfully added '.$domain . ' to hosts file');
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

<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class SetupConfig extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup:config';

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

        $p = Process::fromShellCommandline('echo $HOME');
        $p->run();
        $data = [];
        if($p->isSuccessful()) {
            $home_path = preg_replace('/\n/', '', $p->getOutput());
            $data['home_path'] = $home_path;
        }


        $path = $this->ask('What is your the path to Homestead.yaml?');
        $path = str_replace('Homestead.yaml', '', $path);
        if(str_contains($path, '~')) {
            $path = str_replace('~', $home_path, $path);
        }

        if(file_exists($path .'/Homestead.yaml')) {
            $data['path'] = $path;
            $this->info('Homestead directory saved');
        } else {
            $this->error('Folder not found');
            return;
        }

        $data['hosts_file'] = $this->ask('Where is your host file?',  '/etc/hosts');

        file_put_contents(
            './.homestead.config.json',
            json_encode(
                $data,
                JSON_PRETTY_PRINT
            )
        );

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

<?php

namespace App\Commands;

use App\Lib\Config;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class AddApplication extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'add:app {--dry-run}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add Application to homestead';

    protected $yaml = [];
    protected $app_path;
    protected $vm_path;
    protected $domain;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->app_path = $this->ask('What is the local path to your application?');
        $this->domain = $this->ask('What domain would you like to use?');
        $shouldMapFolder = $this->choice('Do you want to map this folder to individually?', ['yes', 'no'], 'no');
         // make sure we have the config path
        $this->config = Config::get();
        if (!isset($this->config['path'])) {
            $this->error('Homestead Path is not setup.');
            return;
        }

        // add to yaml
        $this->yaml = Yaml::parseFile($this->config['path'].'/Homestead.yaml');
        $yaml_copy = $this->yaml;
        if (collect($this->yaml['sites'])->contains(fn($s) => $s['map'] === $this->domain)) {
            $this->error('Domain already in use');
            return;
        }

        // match folder
        if(!$shouldMapFolder) {
            $this->matchFolders();
        } else {
            $this->mapIndividualFolders();
        }

        $this->yaml['sites'][] = [
            'map' => $this->domain,
            'to' => $this->vm_path
        ];

        if(!$this->option('dry-run')) {
            // create back up of yaml file
            copy($this->config['path'].'/Homestead.yaml', $this->config['path'].'/Homestead.yaml'.'.copy');

            // create new yaml file
            file_put_contents(
                $this->config['path'].'/Homestead.yaml',
                Yaml::dump($this->yaml, 3, 4, Yaml::DUMP_OBJECT_AS_MAP)
            );
            $this->info('Yaml created');

            // run provision
            if($this->choice('Would you like to provision Homestead?', ['yes', 'no'], 'yes') === 'yes') {
                $this->call('provision');        }
            // add to hosts
            if($this->choice('Would you like to add the domain to host file?', ['yes', 'no'], 'yes') === 'yes') {
                $this->call('add:host', ['domain' => $this->domain]);
            }
        } else {
            dump($this->yaml);
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    protected function matchFolders()
    {
        $folders = $this->yaml['folders'][0];

        // get local shared folder
        $parts = explode('/', $folders['map']);
        $top_folder = $parts[count($parts) - 1];

        // get last part of path that is shared with vm and add to vm path
        $path_parts = explode($top_folder, $this->app_path);
        $shared_path = $path_parts[1] ?? null;
        $vm_path = $folders['to'].$shared_path;

        // check if ends with public
        if (!str_ends_with($vm_path, '/public')) {
            $vm_path .= '/public';
        }

        $this->vm_path = $vm_path;
    }

    protected function mapIndividualFolders()
    {

        $parts = explode('/', $this->app_path);
        $top_folder = $parts[count($parts) - 1];
        $mapped_path = '/home/vagrant/'.$top_folder;

        $this->yaml['folders'][] = [
            'map' => $this->app_path,
            'to' => $mapped_path
        ];

        $this->vm_path = $mapped_path;

        // check if ends with public
        if (!str_ends_with($this->vm_path, '/public')) {
            $this->vm_path .= '/public';
        }

    }
}

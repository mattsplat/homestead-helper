<?php


namespace App\Lib;


use Spatie\Ssh\Ssh;
use Symfony\Component\Yaml\Yaml;

class DomainShell
{
    protected $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function run(array $commands)
    {
        return Ssh::create('vagrant', app('yaml')['ip'])
            ->onOutput(function($type, $line) {
                echo $line;
            })->execute(array_merge(['cd '. $this->domain->path], $commands));
    }
}

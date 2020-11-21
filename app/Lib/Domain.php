<?php


namespace App\Lib;


use Symfony\Component\Yaml\Yaml;

class Domain
{
    public $path;
    public $name;
    public function __construct($path, $name)
    {
        $this->path = $path;
        $this->name = $name;
    }

    public static function find($domain)
    {

        $yaml = app('yaml');
        foreach($yaml['sites'] as $site) {
            if($site['map'] === $domain) {
                $app = $site;
            }
        }
        if(!isset($app)) return null;
        $app_path = preg_replace('/\/public$/', '', $app['to']);

        return new self($app_path, $domain);
    }

    public function shell()
    {
        return new DomainShell($this);
    }

}

<?php


namespace App\Lib;


class Config
{
    public static $filename = __DIR__.'/.homestead.config.json.';
    public static $data = [];

    static public function open()
    {
        $data = file_get_contents( static::$filename);
        static::$data = json_decode($data, 1);
    }

    static public function write(array $data)
    {
        $data = file_put_contents(static::$filename, json_encode($data, JSON_PRETTY_PRINT));
        static::$data = $data;
    }

    static function get($name = null)
    {
       if(empty(static::$data)) {
           static::open();
       }

       if($name) {
           return static::$data[$name] ?? null;
       }

       return static::$data;
    }

    static public function add(array $newData)
    {
        $all = static::get();

        foreach($newData as $key => $value) {
            $all[$key] = $value;
        }

        static::write($all);
        static::open();
    }
}

<?php
class Benchmark implements JsonSerializable
{
    private $start;
    private $end;
    private $result;

    private static $bench = array();

    public function __construct($name)
    {
        self::$bench[$name] = $this;
    }

    public function start()
    {
        $this->start = microtime(true);
    }

    public function end()
    {
        $this->end = microtime(true);
        $this->result = $this->end - $this->start;
    }

    public function result()
    {
        return $this->result;
    }

    public function jsonSerialize()
    {
        return $this->result;
    }

    public static function startBench($name)
    {
        $o = new Benchmark($name);
        $o->start();

        return $o;
    }

    public static function getBench()
    {
        return self::$bench;
    }
}

<?php
class Benchmark implements JsonSerializable
{
    const TOTAL = "total";
    const RESULTS = "results";

    private $start;
    private $end;
    private $result;

    private static $bench = array(self::RESULTS => array());

    public function __construct($name)
    {
        if ($name === self::TOTAL)
        {
            return;
        }

        self::$bench[self::RESULTS][$name] = $this;
    }

    public static function total(): self
    {
        $o = new Benchmark(self::TOTAL);
        $o->start();

        self::$bench[self::TOTAL] = $o;

        return $o;
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

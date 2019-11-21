<?php
declare(strict_types=1);

class Benchmark implements JsonSerializable
{
    const TOTAL = "total";
    const RESULTS = "results";

    private $start;
    private $end;
    private $result;

    private static $bench = array(self::RESULTS => array());

    public function __construct(string $name)
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

    public function start(): void
    {
        $this->start = microtime(true);
    }

    public function end(): void
    {
        $this->end = microtime(true);
        $this->result = $this->end - $this->start;
    }

    public function result(): float
    {
        return $this->result;
    }

    public function jsonSerialize(): float
    {
        return $this->result;
    }

    public static function startBench(string $name): self
    {
        $o = new Benchmark($name);
        $o->start();

        return $o;
    }

    public static function getBench(): array
    {
        return self::$bench;
    }
}

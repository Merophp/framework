<?php

class TestEvent
{
    protected string $bar;

    public function __construct($bar)
    {
        $this->bar = $bar;
    }

    public function getBar(): string
    {
        return $this->bar;
    }
}

<?php

namespace leanphp\core;

class Container
{
    private array $definitions = [];
    private array $instances = [];

    public function set(string $id, callable $concrete): void
    {
        $this->definitions[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (!isset($this->instances[$id])) {
            $this->instances[$id] = $this->definitions[$id]($this);
        }
        return $this->instances[$id];
    }
}
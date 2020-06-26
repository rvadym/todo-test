<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver;

abstract class AbstractResolver
{
    abstract public function render(): string;

    public function getHeaders(): array
    {
        return [];
    }
}

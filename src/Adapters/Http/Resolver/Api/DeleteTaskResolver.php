<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use ToDoTest\Adapters\Http\Exception\JsonEncodeErrorException;
use ToDoTest\Application\Aggregate\DeleteTaskAggregate;

class DeleteTaskResolver extends AbstractJsonResolver
{
    /** @var DeleteTaskAggregate */
    private $deleteTaskAggregate;

    public function __construct(
        DeleteTaskAggregate $deleteTaskAggregate
    ) {
        $this->deleteTaskAggregate = $deleteTaskAggregate;
    }

    /**
     * @throws JsonEncodeErrorException
     */
    public function render(): string
    {
        return $this->toJson([]);
    }
}

<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\TaskAggregate;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Query\GetTaskQuery;
use ToDoTest\Application\Exception\TaskNotFoundException;

class GetTaskUseCase
{
    /** @var GetTaskFunc */
    private $getTaskFunc;

    public function __construct(
        GetTaskFunc $getTaskFunc
    ) {
        $this->getTaskFunc = $getTaskFunc;
    }

    /**
     * @throws TaskNotFoundException
     */
    public function execute(GetTaskQuery $query): TaskAggregate
    {
        return new TaskAggregate(
            $this->getTaskFunc->execute($query->getTaskId())
        );
    }
}

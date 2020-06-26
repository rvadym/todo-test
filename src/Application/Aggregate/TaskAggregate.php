<?php declare(strict_types=1);

namespace ToDoTest\Application\Aggregate;

use ToDoTest\Domain\Model\Task;

class TaskAggregate
{
    /** @var Task */
    private $task;

    public function __construct(
        Task $task
    ) {
        $this->task = $task;
    }

    public function getTask(): Task
    {
        return $this->task;
    }
}

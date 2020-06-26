<?php declare(strict_types=1);

namespace ToDoTest\Application\Command;

use ToDoTest\Domain\Model\Task;

class UpdateTaskCommand
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

<?php declare(strict_types=1);

namespace ToDoTest\Application\Command;

use ToDoTest\Domain\ValueObject\TaskDescription;

class CreateTaskCommand
{
    /** @var TaskDescription */
    private $taskDescription;

    public function __construct(
        TaskDescription $taskDescription
    ) {
        $this->taskDescription = $taskDescription;
    }

    public function getTaskDescription(): TaskDescription
    {
        return $this->taskDescription;
    }
}

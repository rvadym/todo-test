<?php declare(strict_types=1);

namespace ToDoTest\Application\Command;

use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;

class UpdateTaskDescriptionCommand
{
    /** @var TaskId */
    private $taskId;

    /** @var TaskDescription */
    private $taskDescription;

    public function __construct(
        TaskId $taskId,
        TaskDescription $taskDescription
    ) {
        $this->taskId = $taskId;
        $this->taskDescription = $taskDescription;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getTaskDescription(): TaskDescription
    {
        return $this->taskDescription;
    }
}

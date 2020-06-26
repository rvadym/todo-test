<?php declare(strict_types=1);

namespace ToDoTest\Domain\Model;

use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class Task
{
    /** @var TaskId */
    private $taskId;

    /** @var TaskStatus */
    private $taskStatus;

    /** @var TaskDescription */
    private $taskDescription;

    public function __construct(
        TaskId $taskId,
        TaskStatus $taskStatus,
        TaskDescription $taskDescription
    ) {
        $this->taskId = $taskId;
        $this->taskStatus = $taskStatus;
        $this->taskDescription = $taskDescription;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getTaskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }

    public function getTaskDescription(): TaskDescription
    {
        return $this->taskDescription;
    }
}

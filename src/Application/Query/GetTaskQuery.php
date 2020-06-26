<?php declare(strict_types=1);

namespace ToDoTest\Application\Query;

use ToDoTest\Domain\ValueObject\TaskId;

class GetTaskQuery
{
    /** @var TaskId */
    private $taskId;

    public function __construct(
        TaskId $taskId
    ) {
        $this->taskId = $taskId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}

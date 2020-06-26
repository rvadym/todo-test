<?php

namespace ToDoTest\Application\Repository\Read;

use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskId;

interface GetTaskRepositoryInterface
{
    /**
     * @throws TaskNotFoundException
     */
    public function getOne(TaskId $taskId): ?Task;
}

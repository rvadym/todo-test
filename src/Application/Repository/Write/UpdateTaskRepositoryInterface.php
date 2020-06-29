<?php

namespace ToDoTest\Application\Repository\Write;

use ToDoTest\Application\Exception\TaskNotUpdatedException;
use ToDoTest\Domain\Model\Task;

interface UpdateTaskRepositoryInterface
{
    /**
     * @throws TaskNotUpdatedException
     */
    public function update(Task $task): void;
}

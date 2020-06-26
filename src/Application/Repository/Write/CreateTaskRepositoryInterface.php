<?php

namespace ToDoTest\Application\Repository\Write;

use ToDoTest\Application\Exception\TaskNotCreatedException;
use ToDoTest\Domain\Model\Task;

interface CreateTaskRepositoryInterface
{
    /**
     * @throws TaskNotCreatedException
     */
    public function create(Task $task): void;
}

<?php declare(strict_types=1);

namespace ToDoTest\Application\Repository\Write;

use ToDoTest\Domain\ValueObject\TaskId;

interface DeleteTaskRepositoryInterface
{
    public function delete(TaskId $taskId): void;
}

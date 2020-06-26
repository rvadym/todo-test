<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Doctrine\Factory;

use ToDoTest\Adapters\Doctrine\Entity\Task as DoctrineTask;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

abstract class TaskFactory
{
    public static function createFromEntity(DoctrineTask $taskEntity): Task
    {
        /** @var string $status */
        $status = $taskEntity->getStatus();

        if ($status === '') {
            $statusEnum = TaskStatusEnum::NO_STATUS();
        } else {
            $statusEnum = TaskStatusEnum::$status();
        }

        return new Task(
            new TaskId($taskEntity->getId()),
            new TaskStatus($statusEnum),
            new TaskDescription($taskEntity->getDescription())
        );
    }

}

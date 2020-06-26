<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\TaskAggregate;
use ToDoTest\Application\Command\CreateTaskCommand;
use ToDoTest\Application\Func\GenerateUuidFunc;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\Exception\TaskNotCreatedException;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class CreateTaskUseCase
{
    /** @var GenerateUuidFunc */
    private $generateUuidFunc;

    /** @var CreateTaskRepositoryInterface */
    private $createTaskRepository;

    public function __construct(
        GenerateUuidFunc $generateUuidFunc,
        CreateTaskRepositoryInterface $createTaskRepository
    ) {
        $this->generateUuidFunc = $generateUuidFunc;
        $this->createTaskRepository = $createTaskRepository;
    }


    /**
     * @throws TaskNotCreatedException
     */
    public function execute(CreateTaskCommand $command): TaskAggregate
    {
        $taskId = new TaskId(
          $this->generateUuidFunc->execute()->toString()
        );

        $taskStatus = new TaskStatus(
            TaskStatusEnum::ACTIVE()
        );

        $task = new Task(
            $taskId,
            $taskStatus,
            $command->getTaskDescription()
        );

        $this->createTaskRepository->create($task);

        return new TaskAggregate($task);
    }
}

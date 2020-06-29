<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\TaskAggregate;
use ToDoTest\Application\Command\ToggleTaskStatusCommand;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Exception\TaskNotUpdatedException;
use ToDoTest\Application\Exception\UnexpectedTaskStatusException;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskStatus;

class ToggleTaskStatusUseCase
{
    /** @var GetTaskFunc */
    private $getTaskFunc;
    
    /** @var UpdateTaskRepositoryInterface */
    private $updateTaskRepository;

    public function __construct(
        GetTaskFunc $getTaskFunc,
        UpdateTaskRepositoryInterface $updateTaskRepository
    ) {
        $this->getTaskFunc = $getTaskFunc;
        $this->updateTaskRepository = $updateTaskRepository;
    }

    /**
     * @throws TaskNotFoundException
     * @throws TaskNotUpdatedException
     * @throws UnexpectedTaskStatusException
     */
    public function execute(ToggleTaskStatusCommand $command): TaskAggregate
    {
        $currentTask = $this->getTaskFunc->execute($command->getTaskId());

        $newTask = new Task(
            $currentTask->getTaskId(),
            $this->toggleStatus(
                $currentTask->getTaskStatus()
            ),
            $currentTask->getTaskDescription()
        );

        $this->updateTaskRepository->update($newTask);

        return new TaskAggregate($newTask);
    }

    /**
     * @throws UnexpectedTaskStatusException
     */
    private function toggleStatus(TaskStatus $taskStatus): TaskStatus
    {
        switch ($taskStatus->getValue()->getValue()) {
            case TaskStatusEnum::ACTIVE()->getValue():
                return new TaskStatus(
                    TaskStatusEnum::DONE()
                );
            case TaskStatusEnum::DONE()->getValue():
                return new TaskStatus(
                    TaskStatusEnum::ACTIVE()
                );
        }

        throw new UnexpectedTaskStatusException();
    }
}

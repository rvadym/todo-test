<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\TaskAggregate;
use ToDoTest\Application\Command\UpdateTaskDescriptionCommand;
use ToDoTest\Application\Exception\TaskNotUpdatedException;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Domain\Model\Task;

class UpdateTaskDescriptionUseCase
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
     */
    public function execute(UpdateTaskDescriptionCommand $command): TaskAggregate
    {
        $currentTask = $this->getTaskFunc->execute($command->getTaskId());
        
        $newTask = new Task(
            $currentTask->getTaskId(),
            $currentTask->getTaskStatus(),
            $command->getTaskDescription()
        );
        
        $this->updateTaskRepository->update($newTask);

        return new TaskAggregate($newTask);
    }
}

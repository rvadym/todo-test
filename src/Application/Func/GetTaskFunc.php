<?php declare(strict_types=1);

namespace ToDoTest\Application\Func;

use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Repository\Read\GetTaskRepositoryInterface;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskId;

class GetTaskFunc
{
    /** @var GetTaskRepositoryInterface */
    private $getTaskRepository;

    public function __construct(
        GetTaskRepositoryInterface $getTaskRepository
    ) {
        $this->getTaskRepository = $getTaskRepository;
    }

    /**
     * @throws TaskNotFoundException
     */
    public function execute(TaskId $taskId): Task
    {
        return $this->getTaskRepository->getOne($taskId);
    }
}

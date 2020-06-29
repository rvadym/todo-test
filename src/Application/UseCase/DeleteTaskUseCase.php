<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\DeleteTaskAggregate;
use ToDoTest\Application\Command\DeleteTaskCommand;
use ToDoTest\Application\Repository\Write\DeleteTaskRepositoryInterface;

class DeleteTaskUseCase
{
    /** @var DeleteTaskRepositoryInterface */
    private $deleteTaskRepository;

    public function __construct(
        DeleteTaskRepositoryInterface $deleteTaskRepository
    ) {
        $this->deleteTaskRepository = $deleteTaskRepository;
    }

    public function execute(DeleteTaskCommand $command): DeleteTaskAggregate
    {
        $this->deleteTaskRepository->delete(
            $command->getTaskId()
        );

        return new DeleteTaskAggregate();
    }
}

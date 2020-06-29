<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Slim\Container;
use ToDoTest\Adapters\Http\Action\Api\CreateTaskAction;
use ToDoTest\Adapters\Http\Action\Api\GetPaginatedTasksAction;
use ToDoTest\Adapters\Http\Action\Api\GetTaskAction;
use ToDoTest\Adapters\Http\Action\Api\UpdateTaskAction;
use ToDoTest\Application\UseCase\CreateTaskUseCase;
use ToDoTest\Application\UseCase\GetPaginatedTasksUseCase;
use ToDoTest\Application\UseCase\GetTaskUseCase;
use ToDoTest\Application\UseCase\UpdateTaskUseCase;

class ActionsDependency extends AbstractDependency
{
    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    public function execute(): void
    {
        $this->bindTaskActions();
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    private function bindTaskActions(): void
    {
        $this->bind(CreateTaskAction::class, function (Container $container): CreateTaskAction
        {
            return new CreateTaskAction(
                $container->get(CreateTaskUseCase::class)
            );
        });
        $this->bind(GetTaskAction::class, function (Container $container): GetTaskAction
        {
            return new GetTaskAction(
                $container->get(GetTaskUseCase::class)
            );
        });
        $this->bind(GetPaginatedTasksAction::class, function (Container $container): GetPaginatedTasksAction
        {
            return new GetPaginatedTasksAction(
                $container->get(GetPaginatedTasksUseCase::class)
            );
        });
        $this->bind(UpdateTaskAction::class, function (Container $container): UpdateTaskAction
        {
            return new UpdateTaskAction(
                $container->get(UpdateTaskUseCase::class)
            );
        });
    }
}

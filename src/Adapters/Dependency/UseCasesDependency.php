<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Slim\Container;
use ToDoTest\Application\Func\GenerateUuidFunc;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Read\GetPaginatedTasksRepositoryInterface;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\DeleteTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Application\UseCase\CreateTaskUseCase;
use ToDoTest\Application\UseCase\GetPaginatedTasksUseCase;
use ToDoTest\Application\UseCase\GetTaskUseCase;
use ToDoTest\Application\UseCase\ToggleTaskStatusUseCase;
use ToDoTest\Application\UseCase\UpdateTaskUseCase;
use ToDoTest\Application\UseCase\DeleteTaskUseCase;

class UseCasesDependency extends AbstractDependency
{
    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    public function execute(): void
    {
        $this->bindTaskUseCases();
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    private function bindTaskUseCases(): void
    {
        $this->bind(CreateTaskUseCase::class, function(Container $container): CreateTaskUseCase
        {
            return new CreateTaskUseCase(
                $container->get(GenerateUuidFunc::class),
                $container->get(CreateTaskRepositoryInterface::class),
            );
        });
        $this->bind(GetTaskUseCase::class, function(Container $container): GetTaskUseCase
        {
            return new GetTaskUseCase(
                $container->get(GetTaskFunc::class),
            );
        });
        $this->bind(GetPaginatedTasksUseCase::class, function(Container $container): GetPaginatedTasksUseCase
        {
            return new GetPaginatedTasksUseCase(
                $container->get(GetPaginatedTasksRepositoryInterface::class),
            );
        });
        $this->bind(UpdateTaskUseCase::class, function(Container $container): UpdateTaskUseCase
        {
            return new UpdateTaskUseCase(
                $container->get(GetTaskFunc::class),
                $container->get(UpdateTaskRepositoryInterface::class),
            );
        });
        $this->bind(ToggleTaskStatusUseCase::class, function(Container $container): ToggleTaskStatusUseCase
        {
            return new ToggleTaskStatusUseCase(
                $container->get(GetTaskFunc::class),
                $container->get(UpdateTaskRepositoryInterface::class),
            );
        });
        $this->bind(DeleteTaskUseCase::class, function(Container $container): DeleteTaskUseCase
        {
            return new DeleteTaskUseCase(
                $container->get(DeleteTaskRepositoryInterface::class),
            );
        });
    }
}

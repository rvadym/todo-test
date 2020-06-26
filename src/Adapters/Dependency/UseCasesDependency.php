<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Slim\Container;
use ToDoTest\Application\Func\GenerateUuidFunc;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Application\UseCase\CreateTaskUseCase;
use ToDoTest\Application\UseCase\GetTaskUseCase;
use ToDoTest\Application\UseCase\UpdateTaskDescriptionUseCase;

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
        $this->bind(UpdateTaskDescriptionUseCase::class, function(Container $container): UpdateTaskDescriptionUseCase
        {
            return new UpdateTaskDescriptionUseCase(
                $container->get(GetTaskFunc::class),
                $container->get(UpdateTaskRepositoryInterface::class),
            );
        });
    }
}

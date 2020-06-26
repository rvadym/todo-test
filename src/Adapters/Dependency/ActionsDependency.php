<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Slim\Container;
use ToDoTest\Adapters\Http\Action\Api\CreateTaskAction;
use ToDoTest\Application\UseCase\CreateTaskUseCase;

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
    }
}

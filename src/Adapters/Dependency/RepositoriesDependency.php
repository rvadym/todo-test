<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Doctrine\ORM\EntityManager;
use Slim\Container;
use ToDoTest\Adapters\Repository\TaskDoctrineRepository;
use ToDoTest\Application\Repository\Read\GetTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;

class RepositoriesDependency extends AbstractDependency
{
    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    public function execute(): void
    {
        $this->bingUserRepos();
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    private function bingUserRepos(): void
    {
        // real

        $this->bind(TaskDoctrineRepository::class, function (Container $container): TaskDoctrineRepository
        {
            return new TaskDoctrineRepository(
                $container->get(EntityManager::class)
            );
        });

        // interfaces

        $this->bind(GetTaskRepositoryInterface::class, function (Container $container): GetTaskRepositoryInterface
        {
            return $container->get(TaskDoctrineRepository::class);
        });
        $this->bind(CreateTaskRepositoryInterface::class, function (Container $container): CreateTaskRepositoryInterface
        {
            return $container->get(TaskDoctrineRepository::class);
        });
        $this->bind(UpdateTaskRepositoryInterface::class, function (Container $container): UpdateTaskRepositoryInterface
        {
            return $container->get(TaskDoctrineRepository::class);
        });
    }
}

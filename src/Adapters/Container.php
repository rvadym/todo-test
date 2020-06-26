<?php declare(strict_types=1);

namespace ToDoTest\Adapters;

use Slim\Container as SlimContainer;
use ToDoTest\Adapters\Dependency\ActionsDependency;
use ToDoTest\Adapters\Dependency\CoreDependency;
use ToDoTest\Adapters\Dependency\FuncDependency;
use ToDoTest\Adapters\Dependency\RepositoriesDependency;
use ToDoTest\Adapters\Dependency\UseCasesDependency;
use ToDoTest\Adapters\Dependency\Exception\DependencyAlreadyBoundException;

class Container extends SlimContainer
{
    /**
     * @throws DependencyAlreadyBoundException
     */
    public function init(): self
    {
        $this->addServices();

        return $this;
    }

    /**
     * @throws DependencyAlreadyBoundException
     */
    private function addServices(): void
    {
        $this->addCore();
        $this->addRepositories();
        $this->addFuncs();
        $this->addUseCases();
        $this->addActions();
    }

    /**
     * Core services needed by other stuff to run.
     *
     * @throws DependencyAlreadyBoundException
     */
    private function addCore(): void
    {
        (new CoreDependency($this))->execute();
    }

    /**
     * Persistent layer repositories.
     *
     * @throws DependencyAlreadyBoundException
     */
    private function addRepositories(): void
    {
        (new RepositoriesDependency($this))->execute();
    }

    /**
     * Business logic functions
     *
     * @throws DependencyAlreadyBoundException
     */
    private function addFuncs(): void
    {
        (new FuncDependency($this))->execute();
    }

    /**
     * Business logic use cases
     *
     * @throws DependencyAlreadyBoundException
     */
    private function addUseCases(): void
    {
        (new UseCasesDependency($this))->execute();
    }

    /**
     * HTTP Interface
     *
     * @throws DependencyAlreadyBoundException
     */
    private function addActions(): void
    {
        (new ActionsDependency($this))->execute();
    }
}

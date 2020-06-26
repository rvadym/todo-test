<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Slim\Container;
use ToDoTest\Application\Func\GenerateUuidFunc;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Read\GetTaskRepositoryInterface;

class FuncDependency extends AbstractDependency
{
    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    public function execute(): void
    {
        $this->bindFuncs();
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    private function bindFuncs(): void
    {
        $this->bind(GetTaskFunc::class, function(Container $container): GetTaskFunc
        {
            return new GetTaskFunc(
                $container->get(GetTaskRepositoryInterface::class)
            );
        });
        $this->bind(GenerateUuidFunc::class, function(Container $container): GenerateUuidFunc
        {
            return new GenerateUuidFunc();
        });
    }
}

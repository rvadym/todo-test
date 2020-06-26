<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Psr\Container\ContainerInterface;
use Closure;

abstract class AbstractDependency
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    protected function bind(string $key, Closure $closure): void
    {
        if ($this->container->has($key)) {
            throw new Exception\DependencyAlreadyBoundException(sprintf(
                'Dependency for key "%s" already set.',
                $key
            ));
        }

        $this->container[$key] = $closure;
    }
}

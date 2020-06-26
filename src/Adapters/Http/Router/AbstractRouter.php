<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Router;

use Psr\Container\ContainerInterface;
use Slim\App;

abstract class AbstractRouter
{
    /** @var App */
    private $app;

    /** @var ContainerInterface */
    private $container;

    public function __construct(
        App $app,
        ContainerInterface $container
    ) {
        $this->app = $app;
        $this->container = $container;
    }

    protected function getApp(): App
    {
        return $this->app;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}

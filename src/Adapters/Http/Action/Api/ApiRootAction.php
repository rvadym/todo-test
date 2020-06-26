<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Resolver\Api\ApiRootResolver;

class ApiRootAction
{
    use ResponseBuilderTrait;

    public function execute(Request $request, Response $response, array $args): Response
    {
        $resolver = new ApiRootResolver();

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response;
    }
}

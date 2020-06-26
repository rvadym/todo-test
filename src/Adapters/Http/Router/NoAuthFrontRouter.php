<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Router;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ToDoTest\Adapters\Http\Action\HomeAction;

class NoAuthFrontRouter extends AbstractRouter
{
    public function execute(): void
    {
        $this->getApp()->get('', function(Request $request, Response $response, array $args): Response
        {
            $action = new HomeAction();

            return $action->execute($request, $response, $args);
        });
    }
}

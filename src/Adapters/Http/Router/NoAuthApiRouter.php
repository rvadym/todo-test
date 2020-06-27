<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Router;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ToDoTest\Adapters\Http\Action\Api\ApiRootAction;
use ToDoTest\Adapters\Http\Action\Api\CreateTaskAction;
use ToDoTest\Adapters\Http\Action\Api\GetPaginatedTasksAction;
use ToDoTest\Adapters\Http\Action\Api\GetTaskAction;

class NoAuthApiRouter extends AbstractRouter
{
    public function execute(): void
    {
        $this->getApp()->get('', function(Request $request, Response $response, array $args): Response
        {
            $action = new ApiRootAction();

            return $action->execute($request, $response, $args);
        });

        $this->getApp()->get('/tasks',  function(Request $request, Response $response, array $args): Response
        {
            return $this->get(GetPaginatedTasksAction::class)->execute($request, $response, $args);
        });

        $this->getApp()->get('/tasks/{id}',  function(Request $request, Response $response, array $args): Response
        {
            return $this->get(GetTaskAction::class)->execute($request, $response, $args);
        });

//        $this->getApp()->put('/tasks/{id}',  function(Request $request, Response $response, array $args): Response
//        {
//            //return $this->get(CreateTaskAction::class)->execute($request, $response, $args);
//        });

        $this->getApp()->post('/tasks',  function(Request $request, Response $response, array $args): Response
        {
            return $this->get(CreateTaskAction::class)->execute($request, $response, $args);
        });

//        $this->getApp()->delete('/tasks/{id}',  function(Request $request, Response $response, array $args): Response
//        {
//            //return $this->get(CreateTaskAction::class)->execute($request, $response, $args);
//        });
    }
}

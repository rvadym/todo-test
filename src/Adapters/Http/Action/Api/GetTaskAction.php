<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\StrictTypeHelperTrait;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Resolver\Api\GetTaskResolver;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\UseCase\GetTaskUseCase;

class GetTaskAction
{
    use ResponseBuilderTrait;
    use StrictTypeHelperTrait;
    use GetTaskTrait;

    private const INDEX_ID = 'id';

    /** @var GetTaskUseCase */
    private $getTask;

    public function __construct(
        GetTaskUseCase $getTask
    ) {
        $this->getTask = $getTask;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     * @throws TaskNotFoundException
     */
    public function execute(Request $request, Response $response, array $args): Response
    {
        $query = $this->convertToQuery($args);
        $aggregate = $this->getTask->execute($query);
        $resolver = new GetTaskResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response;
    }


    protected function getIndexId(): string
    {
        return self::INDEX_ID;
    }

}

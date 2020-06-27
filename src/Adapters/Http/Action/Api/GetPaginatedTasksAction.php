<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rvadym\Paginator\PageQueryBuilderTrait;
use Rvadym\Paginator\ValueObject\Criteria;
use Rvadym\Types\StrictTypeHelperTrait;
use Rvadym\Types\ValueObject\Exception\NotPositiveIntException;
use ToDoTest\Adapters\Http\Exception\InvalidRequestException;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Resolver\Api\GetPaginatedTasksResolver;
use ToDoTest\Application\Query\GetPaginatedTasksQuery;
use ToDoTest\Application\UseCase\GetPaginatedTasksUseCase;

class GetPaginatedTasksAction
{
    use ResponseBuilderTrait;
    use PageQueryBuilderTrait;
    use StrictTypeHelperTrait;

    private const INDEX_DESCRIPTION = 'description';

    /** @var GetPaginatedTasksUseCase */
    private $getPaginatedTasks;

    public function __construct(
        GetPaginatedTasksUseCase $getPaginatedTasks
    ) {
        $this->getPaginatedTasks = $getPaginatedTasks;
    }


    /**
     * @throws InvalidRequestException
     * @throws NotPositiveIntException
     */
    public function execute(Request $request, Response $response, array $args): Response
    {
        $query = $this->convertToQuery($request);
        $aggregate = $this->getPaginatedTasks->execute($query);
        $resolver = new GetPaginatedTasksResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws NotPositiveIntException
     */
    private function convertToQuery(Request $request): GetPaginatedTasksQuery
    {
        $criteriaData = $this->validateAndPrepareRequestData($request);
        $pageQuery = $this->validateAndPreparePageQuery(
            $request,
            new Criteria($criteriaData)
        );

        return new GetPaginatedTasksQuery($pageQuery);
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateAndPrepareRequestData(Request $request): array
    {
        $data = $request->getQueryParams();

        $preparedData = [];

        $preparedData[self::INDEX_DESCRIPTION . '.%like%'] = $data[self::INDEX_DESCRIPTION] ?? null;

        return $this->filterEmpty($preparedData);
    }

}

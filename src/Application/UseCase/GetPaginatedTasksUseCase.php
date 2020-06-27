<?php declare(strict_types=1);

namespace ToDoTest\Application\UseCase;

use ToDoTest\Application\Aggregate\PaginatedTasksAggregate;
use ToDoTest\Application\Query\GetPaginatedTasksQuery;
use ToDoTest\Application\Repository\Read\GetPaginatedTasksRepositoryInterface;

class GetPaginatedTasksUseCase
{
    /** @var GetPaginatedTasksRepositoryInterface */
    private $getPaginatedTasksRepository;

    public function __construct(
        GetPaginatedTasksRepositoryInterface $getPaginatedTasksRepository
    ) {
        $this->getPaginatedTasksRepository = $getPaginatedTasksRepository;
    }

    public function execute(GetPaginatedTasksQuery $query): PaginatedTasksAggregate
    {
        $tasks = $this->getPaginatedTasksRepository->getPaginated(
            $query->getPageQuery()
        );

        return new PaginatedTasksAggregate(
            $tasks,
            $query->getPageQuery()
        );
    }
}

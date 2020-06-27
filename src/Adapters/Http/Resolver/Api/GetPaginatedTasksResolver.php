<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use Rvadym\Paginator\Model\PageQuery;
use Rvadym\Users\Application\Collection\UserCollection;
use Rvadym\Users\Domain\Model\BaseUser;
use ToDoTest\Adapters\Http\Exception\JsonEncodeErrorException;
use ToDoTest\Adapters\Http\Resolver\Api\ResolverIndexes;
use ToDoTest\Adapters\Http\Resolver\Api\PaginatorResolverTrait;
use ToDoTest\Adapters\Http\Resolver\Api\AbstractJsonResolver;
use ToDoTest\Application\Aggregate\PaginatedTasksAggregate;
use ToDoTest\Application\Collection\TaskCollection;
use ToDoTest\Domain\Model\Task;

class GetPaginatedTasksResolver extends AbstractJsonResolver
{
    use PaginatorResolverTrait;

    /** @var PaginatedTasksAggregate */
    private $paginatedTasksAggregate;

    public function __construct(
        PaginatedTasksAggregate $paginatedTasksAggregate
    ) {
        $this->paginatedTasksAggregate = $paginatedTasksAggregate;
    }

    /**
     * @throws JsonEncodeErrorException
     */
    public function render(): string
    {
        $tasks = $this->paginatedTasksAggregate->getTaskCollection();
        $pageQuery = $this->paginatedTasksAggregate->getPageQuery();

        return $this->toJson([
            ResolverIndexes::INDEX_PAGINATOR => $this->pageQueryToArray($pageQuery),
            ResolverIndexes::INDEX_TASKS => $this->tasksToArray($tasks)
        ]);
    }

    private function tasksToArray(TaskCollection $tasks): array
    {
        $data = [];

        /** @var Task $task */
        foreach ($tasks as $task) {
            $data[] = [
                ResolverIndexes::INDEX_TASK_ID => $task->getTaskId()->getValue(),
                ResolverIndexes::INDEX_TASK_STATUS => $task->getTaskStatus()->getValue()->getValue(),
                ResolverIndexes::INDEX_TASK_DESCRIPTION => $task->getTaskDescription()->getValue(),
            ];
        }

        return $data;
    }
}

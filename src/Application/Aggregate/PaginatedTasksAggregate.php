<?php declare(strict_types=1);

namespace ToDoTest\Application\Aggregate;

use Rvadym\Paginator\Model\PageQuery;
use ToDoTest\Application\Collection\TaskCollection;

class PaginatedTasksAggregate
{
    /** @var TaskCollection */
    private $taskCollection;

    /** @var PageQuery */
    private $pageQuery;

    public function __construct(
        TaskCollection $taskCollection,
        PageQuery $pageQuery
    ) {
        $this->taskCollection = $taskCollection;
        $this->pageQuery = $pageQuery;
    }

    public function getTaskCollection(): TaskCollection
    {
        return $this->taskCollection;
    }

    public function getPageQuery(): PageQuery
    {
        return $this->pageQuery;
    }
}

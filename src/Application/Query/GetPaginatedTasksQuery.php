<?php declare(strict_types=1);

namespace ToDoTest\Application\Query;

use Rvadym\Paginator\Model\PageQuery;

class GetPaginatedTasksQuery
{
    /** @var PageQuery */
    private $pageQuery;

    public function __construct(
        PageQuery $pageQuery
    ) {
        $this->pageQuery = $pageQuery;
    }

    public function getPageQuery(): PageQuery
    {
        return $this->pageQuery;
    }
}

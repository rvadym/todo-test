<?php declare(strict_types=1);

namespace ToDoTest\Application\Repository\Read;

use Rvadym\Paginator\Model\PageQuery;
use ToDoTest\Application\Collection\TaskCollection;

interface GetPaginatedTasksRepositoryInterface
{
    public function getPaginated(PageQuery $page): TaskCollection;
}

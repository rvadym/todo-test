<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use Rvadym\Paginator\Model\PageQuery;

trait PaginatorResolverTrait
{
    private function pageQueryToArray(PageQuery $pageQuery): array
    {
        return [
            'offset' => $pageQuery->getOffset()->getValue(),
            'limit' => $pageQuery->getLimit()->getValue(),
            'criteria' => $pageQuery->getCriteria()->getValue(),
        ];
    }
}

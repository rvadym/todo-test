<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

/*final */abstract class ResolverIndexes
{
    /*
     * RESPONSE
     */

    /* paginator */
    public const INDEX_PAGINATOR = 'paginator';

    /* user */
    public const INDEX_TASK = 'task';
    public const INDEX_TASKS = 'tasks';
    public const INDEX_TASK_ID = 'id';
    public const INDEX_TASK_STATUS = 'status';
    public const INDEX_TASK_DESCRIPTION = 'description';
}

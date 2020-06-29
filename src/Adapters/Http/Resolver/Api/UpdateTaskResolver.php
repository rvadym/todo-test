<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use ToDoTest\Application\Aggregate\TaskAggregate;
use ToDoTest\Domain\Model\Task;

class UpdateTaskResolver extends AbstractJsonResolver
{
    /** @var TaskAggregate */
    private $taskAggregate;

    public function __construct(
        TaskAggregate $taskAggregate
    ) {
        $this->taskAggregate = $taskAggregate;
    }

    public function render(): string
    {
        $task = $this->taskAggregate->getTask();

        return $this->toJson([
            ResolverIndexes::INDEX_TASK => $this->taskToArray($task)
        ]);
    }

    private function taskToArray(Task $task): array
    {
        return [
            ResolverIndexes::INDEX_TASK_ID => $task->getTaskId()->getValue(),
            ResolverIndexes::INDEX_TASK_STATUS => $task->getTaskStatus()->getValue()->getValue(),
            ResolverIndexes::INDEX_TASK_DESCRIPTION => $task->getTaskDescription()->getValue(),
        ];
    }
}

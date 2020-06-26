<?php declare(strict_types=1);

namespace ToDoTest\Application\Collection;

use Rvadym\Types\Collection\AbstractCollection;
use ToDoTest\Domain\Model\Task;

class TaskCollection extends AbstractCollection
{
    /** @var Task */
    private $task;

    public function __construct(
        Task ...$tasks
    ) {
        $this->tasks = $tasks;
    }

    protected function getItems(): array
    {
        return $this->tasks;
    }
}

<?php declare(strict_types=1);

namespace Test\ToDoTest\Application\Func;

use PHPUnit\Framework\TestCase;
use Mockery as M;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Read\GetTaskRepositoryInterface;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class GetTaskFuncTest extends TestCase
{
    public function testExecuteSuccess(): void
    {
        $task = new Task(
            $expectedTaskId = new TaskId('__task_id__'),
            $expectedTaskStatus = new TaskStatus(TaskStatusEnum::ACTIVE()),
            $expectedTaskDescription = new TaskDescription('__task_description__'),
        );

        /** @var M\Mock | GetTaskRepositoryInterface $getTaskRepositoryMock */
        $getTaskRepositoryMock = M::mock(GetTaskRepositoryInterface::class);
        $getTaskRepositoryMock->expects('getOne')->andReturn($task);

        $func = new GetTaskFunc($getTaskRepositoryMock);

        $actualTask = $func->execute($expectedTaskId);

        $this->assertEquals($expectedTaskId->getValue(), $actualTask->getTaskId()->getValue());
        $this->assertEquals($expectedTaskStatus->getValue(), $actualTask->getTaskStatus()->getValue());
        $this->assertEquals($expectedTaskDescription->getValue(), $actualTask->getTaskDescription()->getValue());
    }

    public function testExecuteTaskNotFoundException(): void
    {
        $this->expectException(TaskNotFoundException::class);

        $taskId = new TaskId('__task_id__');

        /** @var M\Mock | GetTaskRepositoryInterface $getTaskRepositoryMock */
        $getTaskRepositoryMock = M::mock(GetTaskRepositoryInterface::class);
        $getTaskRepositoryMock->expects('getOne')->andThrow(
            new TaskNotFoundException()
        );

        $func = new GetTaskFunc($getTaskRepositoryMock);

        $func->execute($taskId);
    }
}

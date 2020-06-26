<?php declare(strict_types=1);

namespace Test\ToDoTest\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Mockery as M;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Query\GetTaskQuery;
use ToDoTest\Application\UseCase\GetTaskUseCase;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class GetTaskUseCaseTest extends TestCase
{
    public function testExecuteSuccess(): void
    {
        $task = new Task(
            $expectedTaskId = new TaskId('__task_id__'),
            $expectedTaskStatus = new TaskStatus(TaskStatusEnum::ACTIVE()),
            $expectedTaskDescription = new TaskDescription('__task_description__'),
        );

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->andReturn($task);

        $query = new GetTaskQuery($expectedTaskId);
        $useCase = new GetTaskUseCase($getTaskFuncMock);

        $aggregate = $useCase->execute($query);

        $this->assertEquals($expectedTaskId->getValue(), $aggregate->getTask()->getTaskId()->getValue());
        $this->assertEquals($expectedTaskStatus->getValue(), $aggregate->getTask()->getTaskStatus()->getValue());
        $this->assertEquals($expectedTaskDescription->getValue(), $aggregate->getTask()->getTaskDescription()->getValue());
    }

    public function testExecuteTaskNotFoundException(): void
    {
        $this->expectException(TaskNotFoundException::class);

        $expectedTaskId = new TaskId('__task_id__');

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->andThrow(
            new TaskNotFoundException()
        );

        $query = new GetTaskQuery($expectedTaskId);
        $useCase = new GetTaskUseCase($getTaskFuncMock);

        $useCase->execute($query);
    }
}

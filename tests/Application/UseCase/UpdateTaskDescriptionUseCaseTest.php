<?php declare(strict_types=1);

namespace Test\ToDoTest\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Mockery as M;
use ToDoTest\Application\Command\UpdateTaskCommand;
use ToDoTest\Application\Exception\TaskNotFoundException;
use ToDoTest\Application\Exception\TaskNotUpdatedException;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Application\UseCase\UpdateTaskUseCase;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class UpdateTaskUseCaseTest extends TestCase
{
    public function testExecuteSuccess(): void
    {
        $currentTask = new Task(
            $currentTaskId = new TaskId('__current_task_id__'),
            $currentTaskStatus = new TaskStatus(TaskStatusEnum::ACTIVE()),
            new TaskDescription('__current_task_description__')
        );

        $updatedTaskDescription = new TaskDescription('__task_description_updated__');

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->once()->andReturn($currentTask);

        /** @var M\Mock | UpdateTaskRepositoryInterface $updateTaskRepositoryMock */
        $updateTaskRepositoryMock = M::mock(UpdateTaskRepositoryInterface::class);
        $updateTaskRepositoryMock->expects('update')->once();

        $command = new UpdateTaskCommand(
            $currentTaskId,
            $updatedTaskDescription
        );
        $useCase = new UpdateTaskUseCase($getTaskFuncMock, $updateTaskRepositoryMock);

        $aggregate = $useCase->execute($command);

        $this->assertEquals($currentTaskId->getValue(), $aggregate->getTask()->getTaskId()->getValue());
        $this->assertEquals($currentTaskStatus->getValue(), $aggregate->getTask()->getTaskStatus()->getValue());
        $this->assertEquals($updatedTaskDescription->getValue(), $aggregate->getTask()->getTaskDescription()->getValue());
    }

    public function testExecuteTaskNotFoundException(): void
    {
        $this->expectException(TaskNotFoundException::class);

        $currentTaskId = new TaskId('__current_task_id__');
        $updatedTaskDescription = new TaskDescription('__task_description_updated__');

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->once()->andThrow(
            new TaskNotFoundException()
        );

        /** @var M\Mock | UpdateTaskRepositoryInterface $updateTaskRepositoryMock */
        $updateTaskRepositoryMock = M::mock(UpdateTaskRepositoryInterface::class);
        $updateTaskRepositoryMock->expects('update')->never();

        $command = new UpdateTaskCommand(
            $currentTaskId,
            $updatedTaskDescription
        );
        $useCase = new UpdateTaskUseCase($getTaskFuncMock, $updateTaskRepositoryMock);

        $useCase->execute($command);
    }

    public function testExecuteTaskNotUpdatedException(): void
    {
        $this->expectException(TaskNotUpdatedException::class);

        $currentTask = new Task(
            $currentTaskId = new TaskId('__current_task_id__'),
            $currentTaskStatus = new TaskStatus(TaskStatusEnum::ACTIVE()),
            new TaskDescription('__current_task_description__')
        );

        $updatedTaskDescription = new TaskDescription('__task_description_updated__');

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->once()->andReturn($currentTask);

        /** @var M\Mock | UpdateTaskRepositoryInterface $updateTaskRepositoryMock */
        $updateTaskRepositoryMock = M::mock(UpdateTaskRepositoryInterface::class);
        $updateTaskRepositoryMock->expects('update')->once()->andThrow(
            new TaskNotUpdatedException()
        );

        $command = new UpdateTaskCommand(
            $currentTaskId,
            $updatedTaskDescription
        );
        $useCase = new UpdateTaskUseCase($getTaskFuncMock, $updateTaskRepositoryMock);

        $useCase->execute($command);
    }
}

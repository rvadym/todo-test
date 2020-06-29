<?php declare(strict_types=1);

namespace Test\ToDoTest\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Mockery as M;
use ToDoTest\Application\Command\ToggleTaskStatusCommand;
use ToDoTest\Application\Func\GetTaskFunc;
use ToDoTest\Application\Repository\Write\UpdateTaskRepositoryInterface;
use ToDoTest\Application\UseCase\ToggleTaskStatusUseCase;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\Model\Task;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Domain\ValueObject\TaskStatus;

class ToggleTaskStatusUseCaseTest extends TestCase
{
    /**
     * @dataProvider executeSuccessDataProvider
     */
    public function testExecuteSuccess(TaskStatusEnum $fromStatus, TaskStatusEnum $toStatus): void
    {
        $currentTask = new Task(
            $currentTaskId = new TaskId('__current_task_id__'),
            $currentTaskStatus = new TaskStatus($fromStatus),
            $currentTaskDescription = new TaskDescription('__current_task_description__')
        );

        $updatedTaskStatus = new TaskStatus($toStatus);

        /** @var M\Mock | GetTaskFunc $getTaskFuncMock */
        $getTaskFuncMock = M::mock(GetTaskFunc::class);
        $getTaskFuncMock->expects('execute')->once()->andReturn($currentTask);

        /** @var M\Mock | UpdateTaskRepositoryInterface $updateTaskRepositoryMock */
        $updateTaskRepositoryMock = M::mock(UpdateTaskRepositoryInterface::class);
        $updateTaskRepositoryMock->expects('update')->once();

        $command = new ToggleTaskStatusCommand(
            new TaskId('__existing_task_id__')
        );
        $useCase = new ToggleTaskStatusUseCase(
            $getTaskFuncMock,
            $updateTaskRepositoryMock
        );

        $aggregate = $useCase->execute($command);

        $this->assertEquals($currentTaskId->getValue(), $aggregate->getTask()->getTaskId()->getValue());
        $this->assertEquals($updatedTaskStatus->getValue()->getValue(), $aggregate->getTask()->getTaskStatus()->getValue()->getValue());
        $this->assertEquals($currentTaskDescription->getValue(), $aggregate->getTask()->getTaskDescription()->getValue());
    }

    public function executeSuccessDataProvider(): array
    {
        return [
            'active to done' => [
                TaskStatusEnum::ACTIVE(),
                TaskStatusEnum::DONE(),
            ],
            'done to active' => [
                TaskStatusEnum::ACTIVE(),
                TaskStatusEnum::DONE(),
            ],
        ];
    }
}

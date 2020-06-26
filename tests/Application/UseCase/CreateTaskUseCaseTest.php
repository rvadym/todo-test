<?php declare(strict_types=1);

namespace Test\ToDoTest\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Mockery as M;
use ToDoTest\Application\Command\CreateTaskCommand;
use ToDoTest\Application\Exception\TaskNotCreatedException;
use ToDoTest\Application\Func\GenerateUuidFunc;
use ToDoTest\Application\Repository\Write\CreateTaskRepositoryInterface;
use ToDoTest\Application\UseCase\CreateTaskUseCase;
use ToDoTest\Domain\Enum\TaskStatusEnum;
use ToDoTest\Domain\ValueObject\TaskDescription;

class CreateTaskUseCaseTest extends TestCase
{
    public function testExecuteSuccess(): void
    {
        $expectedTaskDescription = new TaskDescription('__task_description__');

        /** @var M\Mock | CreateTaskRepositoryInterface $createTaskRepositoryMock */
        $createTaskRepositoryMock = M::mock(CreateTaskRepositoryInterface::class);
        $createTaskRepositoryMock->expects('create');

        $generateUuidFunc = new GenerateUuidFunc();

        $command = new CreateTaskCommand($expectedTaskDescription);
        $useCase = new CreateTaskUseCase(
            $generateUuidFunc,
            $createTaskRepositoryMock
        );

        $aggregate = $useCase->execute($command);

        $this->assertEquals(TaskStatusEnum::ACTIVE(), $aggregate->getTask()->getTaskStatus()->getValue());
        $this->assertEquals($expectedTaskDescription->getValue(), $aggregate->getTask()->getTaskDescription()->getValue());
    }

    public function testExecuteTaskNotCreatedException(): void
    {
        $this->expectException(TaskNotCreatedException::class);

        $taskDescription = new TaskDescription('__task_description__');

        /** @var M\Mock | CreateTaskRepositoryInterface $createTaskRepositoryMock */
        $createTaskRepositoryMock = M::mock(CreateTaskRepositoryInterface::class);
        $createTaskRepositoryMock->expects('create')->andThrow(
            new TaskNotCreatedException()
        );

        $generateUuidFunc = new GenerateUuidFunc();

        $command = new CreateTaskCommand($taskDescription);
        $useCase = new CreateTaskUseCase(
            $generateUuidFunc,
            $createTaskRepositoryMock
        );

        $useCase->execute($command);
    }
}

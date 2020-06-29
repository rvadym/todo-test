<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\Validator\GroupValidator;
use Rvadym\Types\ValueNormalizer\StringValueNormalizer;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Resolver\Api\DeleteTaskResolver;
use ToDoTest\Application\Command\DeleteTaskCommand;
use ToDoTest\Application\UseCase\DeleteTaskUseCase;
use ToDoTest\Domain\Validator\ValidatorFactory;
use ToDoTest\Domain\ValueObject\TaskId;

class DeleteTaskAction
{
    use ResponseBuilderTrait;

    private const INDEX_ID = 'id';

    /** @var DeleteTaskUseCase */
    private $deleteTask;

    public function __construct(
        DeleteTaskUseCase $deleteTask
    ) {
        $this->deleteTask = $deleteTask;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    public function execute(Request $request, Response $response, array $args): Response
    {
        $command = $this->convertToCommand($args);
        $aggregate = $this->deleteTask->execute($command);
        $resolver = new DeleteTaskResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response->withStatus(204);
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    protected function convertToCommand(array $args): DeleteTaskCommand
    {
        $valueObjects = $this->requestDataToValueObjects($args);

        $this->validateValueObjects(
            $valueObjects[self::INDEX_ID],
        );

        return new DeleteTaskCommand(
            $valueObjects[self::INDEX_ID],
        );
    }

    protected function requestDataToValueObjects(array $args): array
    {
        $valueObjects = [];

        $valueObjects[self::INDEX_ID] = new TaskId(
            StringValueNormalizer::normalize($args[self::INDEX_ID] ?? null)
        );

        return $valueObjects;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    protected function validateValueObjects(TaskId $taskId): void
    {
        $factory = new ValidatorFactory();

        $groupValidator = new GroupValidator(
            'delete_task',
            $factory->getValidator(self::INDEX_ID, $taskId),
        );

        $groupValidator->validate();

        if ($groupValidator->hasErrors()) {
            throw new RequestValidationException(
                $groupValidator->getErrors()
            );
        }
    }
}

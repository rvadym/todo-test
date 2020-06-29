<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\Validator\GroupValidator;
use Rvadym\Types\ValueNormalizer\StringValueNormalizer;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Resolver\Api\ToggleTaskStatusResolver;
use ToDoTest\Application\Command\ToggleTaskStatusCommand;
use ToDoTest\Application\UseCase\ToggleTaskStatusUseCase;
use ToDoTest\Domain\Validator\ValidatorFactory;
use ToDoTest\Domain\ValueObject\TaskId;
use ToDoTest\Application\Exception\UnexpectedTaskStatusException;
use ToDoTest\Application\Exception\TaskNotUpdatedException;
use ToDoTest\Application\Exception\TaskNotFoundException;

class ToggleTaskStatusAction
{
    use ResponseBuilderTrait;

    private const INDEX_ID = 'id';

    /** @var ToggleTaskStatusUseCase */
    private $toggleTaskStatus;

    public function __construct(
        ToggleTaskStatusUseCase $toggleTaskStatus
    ) {
        $this->toggleTaskStatus = $toggleTaskStatus;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     * @throws TaskNotFoundException
     * @throws TaskNotUpdatedException
     * @throws UnexpectedTaskStatusException
     */
    public function execute(Request $request, Response $response, array $args): Response
    {
        $command = $this->convertToCommand($args);
        $aggregate = $this->toggleTaskStatus->execute($command);
        $resolver = new ToggleTaskStatusResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response->withStatus(200);
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    protected function convertToCommand(array $args): ToggleTaskStatusCommand
    {
        $valueObjects = $this->requestDataToValueObjects($args);

        $this->validateValueObjects(
            $valueObjects[self::INDEX_ID],
        );

        return new ToggleTaskStatusCommand(
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
     * @throws RequestValidationException
     * @throws NoValidatorForTypeException
     */
    protected function validateValueObjects(TaskId $taskId): void
    {
        $factory = new ValidatorFactory();

        $groupValidator = new GroupValidator(
            'update_task',
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

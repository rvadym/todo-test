<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\Validator\GroupValidator;
use Rvadym\Types\ValueNormalizer\StringValueNormalizer;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Resolver\Api\UpdateTaskResolver;
use ToDoTest\Application\Command\UpdateTaskCommand;
use ToDoTest\Application\UseCase\UpdateTaskUseCase;
use ToDoTest\Domain\Validator\ValidatorFactory;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Domain\ValueObject\TaskId;

class UpdateTaskAction
{
    use ResponseBuilderTrait;

    private const INDEX_ID = 'id';
    private const INDEX_DESCRIPTION = 'description';

    /** @var UpdateTaskUseCase */
    private $updateTask;

    public function __construct(
        UpdateTaskUseCase $updateTask
    ) {
        $this->updateTask = $updateTask;
    }

    public function execute(Request $request, Response $response, array $args): Response
    {
        $command = $this->convertToCommand($args, $request);
        $aggregate = $this->updateTask->execute($command);
        $resolver = new UpdateTaskResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response->withStatus(200);
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    protected function convertToCommand(array $args, Request $request): UpdateTaskCommand
    {
        $valueObjects = $this->requestDataToValueObjects($args, $request);

        $this->validateValueObjects(
            $valueObjects[self::INDEX_ID],
            $valueObjects[self::INDEX_DESCRIPTION]
        );

        return new UpdateTaskCommand(
            $valueObjects[self::INDEX_ID],
            $valueObjects[self::INDEX_DESCRIPTION],
        );
    }

    protected function requestDataToValueObjects(array $args, Request $request): array
    {
        $valueObjects = [];
        $data = $request->getParsedBody() ?? [];

        $valueObjects[self::INDEX_ID] = new TaskId(
            StringValueNormalizer::normalize($args[self::INDEX_ID] ?? null)
        );

        $valueObjects[self::INDEX_DESCRIPTION] = new TaskDescription(
            StringValueNormalizer::normalize($data[self::INDEX_DESCRIPTION] ?? '')
        );

        return $valueObjects;
    }

    /**
     * @throws RequestValidationException
     * @throws NoValidatorForTypeException
     */
    protected function validateValueObjects(TaskId $taskId, TaskDescription $taskDescription): void
    {
        $factory = new ValidatorFactory();

        $groupValidator = new GroupValidator(
            'update_task',
            $factory->getValidator(self::INDEX_ID, $taskId),
            $factory->getValidator(self::INDEX_DESCRIPTION, $taskDescription),
        );

        $groupValidator->validate();

        if ($groupValidator->hasErrors()) {
            throw new RequestValidationException(
                $groupValidator->getErrors()
            );
        }
    }
}

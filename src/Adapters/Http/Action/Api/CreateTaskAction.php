<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Rvadym\Types\Validator\GroupValidator;
use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\ValueNormalizer\StringValueNormalizer;
use ToDoTest\Adapters\Http\Action\ResponseBuilderTrait;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Resolver\Api\CrateTaskResolver;
use ToDoTest\Application\Command\CreateTaskCommand;
use ToDoTest\Application\UseCase\CreateTaskUseCase;
use ToDoTest\Domain\Validator\ValidatorFactory;
use ToDoTest\Domain\ValueObject\TaskDescription;
use ToDoTest\Application\Exception\TaskNotCreatedException;

class CreateTaskAction
{
    use ResponseBuilderTrait;

    private const INDEX_DESCRIPTION = 'description';

    /** @var CreateTaskUseCase */
    private $createTask;

    public function __construct(
        CreateTaskUseCase $createTask
    ) {
        $this->createTask = $createTask;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     * @throws TaskNotCreatedException
     */
    public function execute(Request $request, Response $response, array $args): Response
    {
        $command = $this->convertToCommand($request);
        $aggregate = $this->createTask->execute($command);
        $resolver = new CrateTaskResolver($aggregate);

        $response = $this->applyHeaders($resolver, $response);
        $response = $this->applyBody($resolver, $response);

        return $response->withStatus(201);
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    protected function convertToCommand(Request $request): CreateTaskCommand
    {
        $valueObjects = $this->requestDataToValueObjects($request);

        $this->validateValueObjects(
            $valueObjects[self::INDEX_DESCRIPTION]
        );

        return new CreateTaskCommand(
            $valueObjects[self::INDEX_DESCRIPTION]
        );
    }

    protected function requestDataToValueObjects(Request $request): array
    {
        $valueObjects = [];
        $data = $request->getParsedBody() ?? [];

        $valueObjects[self::INDEX_DESCRIPTION] = new TaskDescription(
            StringValueNormalizer::normalize($data[self::INDEX_DESCRIPTION] ?? '')
        );

        return $valueObjects;
    }

    /**
     * @throws RequestValidationException
     * @throws NoValidatorForTypeException
     */
    protected function validateValueObjects(TaskDescription $taskDescription): void
    {
        $factory = new ValidatorFactory();

        $groupValidator = new GroupValidator(
            'create_task',
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

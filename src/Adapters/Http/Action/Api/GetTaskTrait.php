<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Action\Api;

use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\Validator\GroupValidator;
use Rvadym\Types\ValueNormalizer\StringValueNormalizer;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Application\Query\GetTaskQuery;
use ToDoTest\Domain\Validator\ValidatorFactory;
use ToDoTest\Domain\ValueObject\TaskId;

trait GetTaskTrait
{
    abstract protected function getIndexId(): string;

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    private function convertToQuery(array $args): GetTaskQuery
    {
        $valueObjects = $this->requestArgsToValueObjects($args);

        $this->validateQueryValueObjects($valueObjects);

        return new GetTaskQuery(
            $valueObjects[$this->getIndexId()]
        );
    }

    private function requestArgsToValueObjects(array $args): array
    {
        $valueObjects = [];

        $valueObjects[$this->getIndexId()] = new TaskId(
            StringValueNormalizer::normalize($args[$this->getIndexId()] ?? null)
        );

        return $valueObjects;
    }

    /**
     * @throws NoValidatorForTypeException
     * @throws RequestValidationException
     */
    private function validateQueryValueObjects(array $valueObjects): void
    {
        $factory = new ValidatorFactory();

        $groupValidator = new GroupValidator(
            'get_task',
            $factory->getValidator(
                $this->getIndexId(),
                $valueObjects[$this->getIndexId()]
            )
        );

        $groupValidator->validate();

        if ($groupValidator->hasErrors()) {
            throw new RequestValidationException(
                $groupValidator->getErrors()
            );
        }
    }
}

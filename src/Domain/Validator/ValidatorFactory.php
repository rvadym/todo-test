<?php declare(strict_types=1);

namespace ToDoTest\Domain\Validator;

use Rvadym\Types\Exception\NoValidatorForTypeException;
use Rvadym\Types\Validator\ValidatorInterface;
use Rvadym\Types\ValueObject\ValueObjectInterface;
use Rvadym\Types\Validator\ValidatorFactory as ValidatorFactoryBase;

class ValidatorFactory extends ValidatorFactoryBase
{
    /**
     * @throws NoValidatorForTypeException
     */
    public function getValidator(string $key, ValueObjectInterface $valueObject): ValidatorInterface
    {
        /*if (is_a($valueObject, UserRole::class)) {
            return new UserRoleValidator($key, $valueObject);
        }*/

        return ValidatorFactoryBase::getValidator($key, $valueObject);
    }
}

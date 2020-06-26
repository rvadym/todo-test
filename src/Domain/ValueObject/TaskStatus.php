<?php declare(strict_types=1);

namespace ToDoTest\Domain\ValueObject;

use ToDoTest\Domain\Enum\TaskStatusEnum;

class TaskStatus
{
    /** @var TaskStatusEnum */
    private $value;

    public function __construct(
        TaskStatusEnum $value
    ) {
        $this->value = $value;
    }

    public function getValue(): TaskStatusEnum
    {
        return $this->value;
    }

    public static function getDefaultValue()
    {
        return TaskStatusEnum::NO_STATUS();
    }

    public function __toString(): string
    {
        return $this->getValue()->getValue();
    }

}

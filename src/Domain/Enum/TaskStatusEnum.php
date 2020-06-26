<?php declare(strict_types=1);

namespace ToDoTest\Domain\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TaskStatusEnum NO_STATUS()
 * @method static TaskStatusEnum ACTIVE()
 * @method static TaskStatusEnum DONE()
 */
class TaskStatusEnum extends Enum
{
    private const NO_STATUS = '';
    private const ACTIVE = 'NEW';
    private const DONE = 'DONE';
}

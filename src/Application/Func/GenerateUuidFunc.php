<?php declare(strict_types=1);

namespace ToDoTest\Application\Func;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GenerateUuidFunc
{
    public function execute(): UuidInterface
    {
        return Uuid::uuid4();
    }
}

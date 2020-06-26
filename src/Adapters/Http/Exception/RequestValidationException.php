<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Exception;

use Exception;
use Throwable;

class RequestValidationException extends Exception
{
    /** @var array */
    private $validationErrors;

    public function __construct(
        array $validationErrors,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        $this->validationErrors = $validationErrors;
        $this->message = json_encode($this->validationErrors);
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}

<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use ToDoTest\Adapters\Http\Exception\JsonEncodeErrorException;

class ApiRootResolver extends AbstractJsonResolver
{
    /**
     * @throws JsonEncodeErrorException
     */
    public function render(): string
    {
        return $this->toJson([
            'api' => [
                'name' => 'ToDoTest',
                'version' => 'v1',
            ]
        ]);
    }
}

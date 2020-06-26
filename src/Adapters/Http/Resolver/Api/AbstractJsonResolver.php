<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Http\Resolver\Api;

use ToDoTest\Adapters\Http\Exception\JsonEncodeErrorException;
use ToDoTest\Adapters\Http\Resolver\AbstractResolver;

abstract class AbstractJsonResolver extends AbstractResolver
{
    public function getHeaders(): array
    {
        $resolverHeaders = parent::getHeaders();

        $jsonResolverHeaders = [
            'Content-Type' => 'application/json',
        ];

        return array_merge(
            $resolverHeaders,
            $jsonResolverHeaders
        );
    }

    /**
     * @throws JsonEncodeErrorException
     */
    protected function toJson(array $data): string
    {
        $json = json_encode($data);

        $jsonLastErrorCode = json_last_error();
        $jsonLastErrorMessage = json_last_error_msg();

        if (JSON_ERROR_NONE !== $jsonLastErrorCode) {
            throw new JsonEncodeErrorException(
                $jsonLastErrorMessage,
                $jsonLastErrorCode
            );
        }

        return $json;
    }
}

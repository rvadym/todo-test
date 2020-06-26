<?php

namespace ToDoTest\Adapters\Http\Action;

use Psr\Http\Message\ResponseInterface as Response;
use ToDoTest\Adapters\Http\Resolver\AbstractResolver;

trait ResponseBuilderTrait
{
    private function applyHeaders(AbstractResolver $resolver, Response $response): Response
    {
        /**
         * @var string $headerName
         * @var string $headerValue
         */
        foreach ($resolver->getHeaders() as $headerName => $headerValue) {
            $response = $response->withHeader($headerName, $headerValue);
        }

        return $response;
    }

    private function applyBody(AbstractResolver $resolver, Response $response): Response
    {
        $response->getBody()->write(
            $resolver->render()
        );

        return $response;
    }
}

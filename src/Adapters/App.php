<?php declare(strict_types=1);

namespace ToDoTest\Adapters;

use Exception;
use Rvadym\Users\Application\Exception\EmailAlreadyUsedException;
use Rvadym\Types\Exception\NotFoundException as DataNotFoundException;
use ToDoTest\Adapters\Http\Exception\RequestValidationException;
use ToDoTest\Adapters\Http\Router\NoAuthApiRouter;
use ToDoTest\Adapters\Http\Router\NoAuthFrontRouter;
use Throwable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ToDoTest\Adapters\Http\Middleware\AllowCorsMiddleware;
use Slim\App as SlimApp;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use Slim\Http\Body;

class App extends SlimApp
{
    /*  */
    private const API_V1_PREFIX = '/api/v1';

    /* Exception response keys */
    private const KEY_VALIDATION_ERRORS = 'validation_errors';
    private const KEY_RUNTIME_ERRORS = 'runtime_errors';
    private const KEY_NOT_FOUND = 'not_found';
    private const KEY_NO_ACCESS = 'no_access';

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws Throwable
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function process(Request $request, Response $response)
    {
        $response = parent::process($request, $response);

        try {
            $this->flushORM();
        } catch (ORMException | OptimisticLockException | Throwable $e) {
            // TODO work with exception

            throw $e;
        }

        return $response;
    }

    public function init(): self
    {
        $this->addRoutes();

        return $this;
    }

    protected function addRoutes(): void
    {
        $this->addNoAuthRoutes();
        $this->addAuthRoutes();
    }

    /**
     * Routes which DO NOT require auth
     */
    protected function addNoAuthRoutes(): void
    {
        $this->addNoAuthApiRoutes();
        $this->addNoAuthFrontRoutes();
    }

    /**
     * Routes which DO require auth
     */
    protected function addAuthRoutes(): void
    {
        $this->addAuthApiRoutes();
        $this->addAuthFrontRoutes();
    }

    /**
     * API NO auth routes.
     */
    protected function addNoAuthApiRoutes(): void
    {
        $this->group(self::API_V1_PREFIX, function() {
            (new NoAuthApiRouter($this, $this->getContainer()))->execute();
        })
            ->add(new AllowCorsMiddleware());
    }

    /**
     * WEB UI NO auth routes
     */
    protected function addNoAuthFrontRoutes(): void
    {
        $this->group('/', function() {
            (new NoAuthFrontRouter($this, $this->getContainer()))->execute();
        });
    }

    /**
     * API auth routes
     */
    protected function addAuthApiRoutes(): void
    {
        /*$this->group(self::API_V1_PREFIX, function() {
            (new AuthApiRouter($this, $this->getContainer()))->execute();
        })
            ->add(new AllowCorsMiddleware());*/
    }

    /**
     * WEB UI auth routes
     */
    protected function addAuthFrontRoutes(): void
    {
//        $this->group('/admin', function() use ($c) {});
    }

    /**
     * @throws Exception
     */
    protected function handleException(Exception $e, Request $request, Response $response)
    {
        if (is_a($e, RequestValidationException::class)) {
            return $this->getValidationExceptionResponse($response, $e);
        }

        if (is_a($e, EmailAlreadyUsedException::class)) {
            return $this->getEmailAlreadyUsedExceptionResponse($response, $e);
        }

        if (is_a($e, DataNotFoundException::class)) {
            return $this->getDataNotFoundExceptionExceptionResponse($response, $e);
        }

        // default exception handler
        return parent::handleException($e, $request, $response);

        /*
         * Drivers level exceptions to be caught:
         *
         * use Doctrine\ORM\ORMException;
         * use Doctrine\ORM\OptimisticLockException;
         * use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
         */

        /*
         * Application level exceptions to be caught:
         *
         * - validations:
         *
         * - persistence layer
         */
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function flushORM(): void
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get(EntityManager::class);
        $em->flush(); // Ignored by Doctrine if there is nothing to do.
        $em->close();
    }

    private function getValidationExceptionResponse(Response $response, Exception $e): Response
    {
        return $this->getExceptionResponse(
            $response,
            $e,
            self::KEY_VALIDATION_ERRORS,
            400 // bad request
        );
    }

    private function getEmailAlreadyUsedExceptionResponse(Response $response, Exception $e): Response
    {
        return $this->getExceptionResponse(
            $response,
            $e,
            self::KEY_RUNTIME_ERRORS,
            409 // Conflict (for duplicates)
        );
    }

    private function getDataNotFoundExceptionExceptionResponse(Response $response, Exception $e): Response
    {
        return $this->getExceptionResponse(
            $response,
            $e,
            self::KEY_NOT_FOUND,
            404
        );
    }

    private function getExceptionResponse(Response $response, Exception $e, string $key, int $code): Response
    {
        $body = $this->getExceptionResponseBody($e, $key);

        return $response
            ->withStatus($code)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }

    private function getExceptionResponseBody(Exception $e, string $key): Body
    {
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write(sprintf(
            '{"%s": %s}',
            $key,
            $e->getMessage()
        ));

        return $body;
    }
}

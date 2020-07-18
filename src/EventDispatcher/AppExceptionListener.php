<?php

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class AppExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['violationsToJson'],['exceptionToJson']]
        ];
    }

    public function violationsToJson(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationFailedException) {
            return;
        }

        $errors = $exception->getViolations();

        $violations = array_map(fn(ConstraintViolationInterface $violation) => [
            'property' => $violation->getPropertyPath(),
            'message' => $violation->getMessage()
        ], iterator_to_array($errors));

        $json = [
            'message' => 'erros de validacao foram encontrados',
            'status' => Response::HTTP_BAD_REQUEST,
            'violations' => $violations
        ];

        $event->setResponse(new JsonResponse($json, Response::HTTP_BAD_REQUEST));
    }

    public function exceptionToJson(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $code = 500;

        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $json = [
            'message' => $exception->getMessage(),
            'status' => $code
        ];

        $event->setResponse(new JsonResponse($json, $code));
    }
}

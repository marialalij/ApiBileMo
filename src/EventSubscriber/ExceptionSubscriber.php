<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundHttpException) {
            $message = 'Object not found: Invalid route or resource ID';
            $status = $exception->getStatusCode();
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $message = 'This method is not allowed for this route';
            $status = $exception->getStatusCode();
        } elseif ($exception instanceof $exception instanceof BadRequestHttpException) {
            $message = $exception->getMessage();
            $status = 400;
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $message = 'You are not allowed to access to this page';
            $status = 403;
        } elseif ($exception) {
            $message = 'HTTP_INTERNAL_SERVER_ERROR';
            $status = 500;
        }

        $data = [
            'code' => $status,
            'message' => $message,
        ];

        $response = new JsonResponse($data, $status);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}

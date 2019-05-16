<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        // get the exception object from the received event
        $exception = $event->getException();
        $response = new JsonResponse();

        // generate error response data body
        $errorCode = method_exists($exception, 'getCode') ? $exception->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $responseData = ['status' => $errorCode, 'message' => $exception->getMessage()];

        // check if in exception exist custom error data
        $errorData = method_exists($exception, 'getData') ? $exception->getData() : null;

        if ($errorData) {
            $responseData['data'] = $errorData;
        }

        // Customize your response object to display the exception details
        $response->setContent(json_encode($responseData));

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
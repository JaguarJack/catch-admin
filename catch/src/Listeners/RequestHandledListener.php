<?php

namespace Catch\Listeners;

use Catch\Enums\Code;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse;

class RequestHandledListener
{
    /**
     * Handle the event.
     *
     * @param RequestHandled $event
     * @return void
     */
    public function handle(RequestHandled $event): void
    {
        $response = $event->response;

        if ($response instanceof JsonResponse) {
            $exception = $response->exception;

            if ($response->getStatusCode() == SymfonyResponse::HTTP_OK && ! $exception) {
                $response->setData($this->formatData($response->getData()));
            }
        }
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function formatData(mixed $data): array
    {
        $responseData = [
            'code' => Code::SUCCESS->value(),
            'message' => Code::SUCCESS->message(),
        ];

        if (is_object($data) && property_exists($data, 'per_page')
            && property_exists($data, 'total')
            && property_exists($data, 'current_page')) {
            $responseData['data'] = $data->data;
            $responseData['total'] = $data->total;
            $responseData['limit'] = $data->per_page;
            $responseData['page'] = $data->current_page;

            return $responseData;
        }

        $responseData['data'] = $data;

        return $responseData;
    }
}

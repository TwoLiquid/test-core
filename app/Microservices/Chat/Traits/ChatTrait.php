<?php

namespace App\Microservices\Chat\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Chat\Responses\BaseResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait ChatTrait
 *
 * @package App\Microservices\Chat\Traits
 */
trait ChatTrait
{
    /**
     * @param int $chatId
     * @param int|null $parentId
     * @param int|null $typeId
     * @param string $text
     * @param array $files
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeChatMessage(
        int $chatId,
        ?int $parentId,
        ?int $typeId,
        string $text,
        array $files
    ) : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/chats/' . $chatId . '/messages', [
                    'form_params' => [
                        'parent_id' => $parentId,
                        'type_id'   => $typeId,
                        'text'      => $text,
                        'files'     => $files
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/chat/chat.' . __FUNCTION__)
            );
        }
    }
}

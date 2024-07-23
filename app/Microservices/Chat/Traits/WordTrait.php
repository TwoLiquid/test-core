<?php

namespace App\Microservices\Chat\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Chat\Responses\BaseResponse;
use App\Microservices\Chat\Responses\DirectPaymentWordCollectionResponse;
use App\Microservices\Chat\Responses\OffensiveWordCollectionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait WordTrait
 *
 * @package App\Microservices\Chat\Traits
 */
trait WordTrait
{
    /**
     * @param string $languageCode
     * @param string|null $search
     *
     * @return OffensiveWordCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getOffensiveWords(
        string $languageCode,
        ?string $search
    ) : OffensiveWordCollectionResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/offensive/words', [
                    'query' => [
                        'language_code' => $languageCode,
                        'search'        => $search
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new OffensiveWordCollectionResponse(
                $responseData->offensive_words,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/chat/word.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $languageCode
     * @param array $words
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateOffensiveWords(
        string $languageCode,
        array $words
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
                'PATCH',
                $this->apiUrl . '/offensive/words/update/many', [
                    'form_params' => [
                        'language_code' => $languageCode,
                        'words'         => $words
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
                trans('exceptions/microservice/chat/word.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $languageCode
     * @param string|null $search
     *
     * @return DirectPaymentWordCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getDirectPaymentWords(
        string $languageCode,
        ?string $search
    ) : DirectPaymentWordCollectionResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/direct/payment/words', [
                    'query' => [
                        'language_code' => $languageCode,
                        'search'        => $search
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new DirectPaymentWordCollectionResponse(
                $responseData->direct_payment_words,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/chat/word.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $languageCode
     * @param array $words
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateDirectPaymentWords(
        string $languageCode,
        array $words
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
                'PATCH',
                $this->apiUrl . '/direct/payment/words/update/many', [
                    'form_params' => [
                        'language_code' => $languageCode,
                        'words'         => $words
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
                trans('exceptions/microservice/chat/word.' . __FUNCTION__)
            );
        }
    }
}

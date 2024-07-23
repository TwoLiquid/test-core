<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\BaseResponse;
use App\Microservices\Media\Responses\PaymentMethodImageCollectionResponse;
use App\Microservices\Media\Responses\PaymentMethodImageResponse;
use App\Models\MySql\Payment\PaymentMethod;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait PaymentMethodMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait PaymentMethodMediaTrait
{
    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return PaymentMethodImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getPaymentMethodImages(
        PaymentMethod $paymentMethod
    ) : PaymentMethodImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/payment/method/' . $paymentMethod->id . '/images'
            );

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new PaymentMethodImageCollectionResponse(
                $responseData->payment_method_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/paymentMethod.' . __FUNCTION__)
            );
        }
    }


    /**
     * @param array $paymentMethodsIds
     *
     * @return PaymentMethodImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getPaymentMethodsImages(
        array $paymentMethodsIds
    ) : PaymentMethodImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/payment/methods/images', [
                'form_params' => [
                    'payment_methods_ids' => $paymentMethodsIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new PaymentMethodImageCollectionResponse(
                $responseData->payment_method_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/paymentMethod.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return PaymentMethodImageResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storePaymentMethodImage(
        PaymentMethod $paymentMethod,
        string $content,
        string $mime,
        string $extension
    ) : PaymentMethodImageResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/payment/method/' . $paymentMethod->id . '/image', [
                'form_params' => [
                    'content'   => $content,
                    'mime'      => $mime,
                    'extension' => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new PaymentMethodImageResponse(
                $responseData->payment_method_image,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/paymentMethod.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteForPaymentMethod(
        PaymentMethod $paymentMethod
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/payment/method/' . $paymentMethod->id . '/images'
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
                trans('exceptions/microservice/media/paymentMethod.' . __FUNCTION__)
            );
        }
    }
}

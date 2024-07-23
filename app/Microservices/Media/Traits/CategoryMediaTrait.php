<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\CategoryIconCollectionResponse;
use App\Microservices\Media\Responses\CategoryIconResponse;
use App\Models\MySql\Category;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait CategoryMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait CategoryMediaTrait
{
    /**
     * @param Category $category
     *
     * @return CategoryIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getCategoryIcons(
        Category $category
    ) : CategoryIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/category/' . $category->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new CategoryIconCollectionResponse(
                $responseData->category_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/category.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $categoriesIds
     *
     * @return CategoryIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getCategoriesIcons(
        array $categoriesIds
    ) : CategoryIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/categories/icons', [
                'form_params' => [
                    'categories_ids' => $categoriesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new CategoryIconCollectionResponse(
                $responseData->category_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/category.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Category $category
     * @param string $content
     * @param string $extension
     * @param string $mime
     *
     * @return CategoryIconResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeCategoryIcon(
        Category $category,
        string $content,
        string $extension,
        string $mime
    ) : ?CategoryIconResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/category/' . $category->id . '/icon', [
                'form_params' => [
                    'content'       => $content,
                    'extension'     => $extension,
                    'mime'          => $mime
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if ($responseData->category_icon) {
                return new CategoryIconResponse(
                    $responseData->category_icon,
                    $responseData->message
                );
            }

            return null;
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/category.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Category $category
     *
     * @return CategoryIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteCategoryIcons(
        Category $category
    ) : CategoryIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/category/' . $category->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new CategoryIconCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/category.' . __FUNCTION__)
            );
        }
    }
}

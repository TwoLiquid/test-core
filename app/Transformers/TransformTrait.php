<?php

namespace App\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;

/**
 * Trait TransformTrait
 *
 * @package App\Transformers
 */
trait TransformTrait
{
    /**
     * @return Manager
     */
    protected function getTransformManager() : Manager
    {
        return (new Manager)->setSerializer(new CustomArraySerializer);
    }

    /**
     * @param mixed $item
     * @param BaseTransformer $transformer
     * @param string|null $resourceKey
     *
     * @return array|null
     */
    protected function transformItem(
        mixed $item,
        BaseTransformer $transformer,
        string $resourceKey = null
    ) : ?array
    {
        $resourceKey = ($resourceKey === null ? $transformer->getItemKey() : $resourceKey);

        if ($item === null) {
            return ($resourceKey === null) ? null : [$resourceKey => null];
        }

        return $this->transformResource(new Item($item, $transformer, $resourceKey));
    }

    /**
     * @param mixed $items
     * @param BaseTransformer $transformer
     * @param string|null $resourceKey
     *
     * @return array
     */
    protected function transformCollection(
        mixed $items,
        BaseTransformer $transformer,
        string $resourceKey = null
    ) : array
    {
        $resourceKey = ($resourceKey === null ? $transformer->getCollectionKey() : $resourceKey);

        return $this->transformResource(new Collection($items, $transformer, $resourceKey));
    }

    /**
     * @param ResourceAbstract $resource
     * @return array
     */
    private function transformResource(ResourceAbstract $resource) : array
    {
        $transformManager = $this->getTransformManager();

        return $transformManager->createData($resource)->toArray();
    }
}

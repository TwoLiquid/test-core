<?php

namespace App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest;

use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'languages',
        'vybe_statuses',
        'request_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeVybeStatuses() : ?Collection
    {
        $vybeStatuses = VybeStatusList::getItems();

        return $this->collection($vybeStatuses, new VybeStatusTransformer);
    }


    /**
     * @return Collection|null
     */
    public function includeRequestStatuses() : ?Collection
    {
        $requestStatuses = RequestStatusList::getItems();

        return $this->collection($requestStatuses, new RequestStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}

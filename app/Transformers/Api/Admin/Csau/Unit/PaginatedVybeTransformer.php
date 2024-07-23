<?php

namespace App\Transformers\Api\Admin\Csau\Unit;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PaginatedUnitTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Unit
 */
class PaginatedVybeTransformer extends BaseTransformer
{
    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'data'
    ];

    /**
     * PaginatedVybeTransformer constructor
     *
     * @param int $page
     * @param int $perPage
     */
    public function __construct(
        int $page = 1,
        int $perPage = 18
    )
    {
        /** @var int page */
        $this->page = $page;

        /** @var int perPage */
        $this->perPage = $perPage;
    }

    /**
     * @param EloquentCollection $vybes
     *
     * @return array[]
     */
    public function transform(EloquentCollection $vybes) : array
    {
        return [
            'pagination' => [
                'page'     => $this->page,
                'by'       => $this->perPage,
                'total'    => $vybes->count(),
                'lastPage' => ceil($vybes->count() / $this->perPage)
            ]
        ];
    }

    /**
     * @param EloquentCollection $vybes
     *
     * @return Collection|null
     */
    public function includeData(EloquentCollection $vybes) : ?Collection
    {
        return $this->collection(
            $vybes->forPage($this->page, $this->perPage),
            new VybeTransformer
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}

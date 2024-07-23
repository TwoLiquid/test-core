<?php

namespace App\Transformers\Api\Guest\Profile\Vybe;

use App\Transformers\Api\Guest\Profile\Vybe\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PaginatedVybeTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe
 */
class PaginatedVybeTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybes;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var int
     */
    protected int $page;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'data'
    ];

    /**
     * PaginatedVybeTransformer constructor
     *
     * @param EloquentCollection $vybes
     * @param int $perPage
     * @param int $page
     */
    public function __construct(
        EloquentCollection $vybes,
        int $perPage,
        int $page = 1
    )
    {
        $this->vybes = $vybes;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    /**
     * @param EloquentCollection $vybes
     *
     * @return array
     */
    public function transform(EloquentCollection $vybes) : array
    {
        return [
            'pagination' => [
                'page'  => $this->page,
                'by'    => $this->perPage,
                'total' => ceil($vybes->count() / $this->perPage)
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

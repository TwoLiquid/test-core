<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Models\MongoDb\User\Billing\ExcludeTaxHistory;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class ExcludeTaxHistoryTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class ExcludeTaxHistoryTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $adminAvatars;

    /**
     * ExcludeTaxHistoryTransformer constructor
     *
     * @param Collection|null $adminAvatars
     */
    public function __construct(
        Collection $adminAvatars = null
    )
    {
        /** @var Collection adminAvatars */
        $this->adminAvatars = $adminAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'admin'
    ];

    /**
     * @param ExcludeTaxHistory $excludeTaxHistory
     *
     * @return array
     */
    public function transform(ExcludeTaxHistory $excludeTaxHistory) : array
    {
        return [
            'id'         => $excludeTaxHistory->_id,
            'admin_id'   => $excludeTaxHistory->admin_id,
            'value'      => $excludeTaxHistory->value,
            'created_at' => $excludeTaxHistory->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param ExcludeTaxHistory $excludeTaxHistory
     *
     * @return Item|null
     */
    public function includeAdmin(ExcludeTaxHistory $excludeTaxHistory): ?Item
    {
        $admin = null;

        if ($excludeTaxHistory->relationLoaded('admin')) {
            $admin = $excludeTaxHistory->admin;
        }

        return $admin ?
            $this->item(
                $admin,
                new AdminTransformer(
                    $this->adminAvatars
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'exclude_tax_history';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'exclude_tax_history';
    }
}

<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VatNumberProofTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class VatNumberProofTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $adminAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vatNumberProofImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vatNumberProofDocuments;

    /**
     * VatNumberProofTransformer constructor
     *
     * @param EloquentCollection|null $adminAvatars
     * @param EloquentCollection|null $vatNumberProofImages
     * @param EloquentCollection|null $vatNumberProofDocuments
     */
    public function __construct(
        EloquentCollection $adminAvatars = null,
        EloquentCollection $vatNumberProofImages = null,
        EloquentCollection $vatNumberProofDocuments = null
    )
    {
        /** @var EloquentCollection adminAvatars */
        $this->adminAvatars = $adminAvatars;

        /** @var EloquentCollection vatNumberProofImages */
        $this->vatNumberProofImages = $vatNumberProofImages;

        /** @var EloquentCollection vatNumberProofDocuments */
        $this->vatNumberProofDocuments = $vatNumberProofDocuments;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'files',
        'country_place',
        'admin',
        'status',
        'exclude_tax_history'
    ];

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return array
     */
    public function transform(VatNumberProof $vatNumberProof) : array
    {
        return [
            'id'                 => $vatNumberProof->_id,
            'company_name'       => $vatNumberProof->company_name,
            'vat_number'         => $vatNumberProof->vat_number,
            'exclude_tax'        => $vatNumberProof->exclude_tax,
            'action_date'        => $vatNumberProof->action_date,
            'exclude_tax_date'   => $vatNumberProof->exclude_tax_date,
            'status_change_date' => $vatNumberProof->status_change_date,
            'created_at'         => $vatNumberProof->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Collection|null
     */
    public function includeFiles(VatNumberProof $vatNumberProof) : ?Collection
    {
        $vatNumberProofFiles = new EloquentCollection();

        if ($this->vatNumberProofImages) {
            $images = $this->vatNumberProofImages->filter(function ($item) use ($vatNumberProof) {
                return $item->vat_number_proof_id == $vatNumberProof->_id;
            });

            foreach ($images as $image) {
                $vatNumberProofFiles->push(
                    $image
                );
            }
        }

        if ($this->vatNumberProofDocuments) {
            $documents = $this->vatNumberProofDocuments->filter(function ($item) use ($vatNumberProof) {
                return $item->vat_number_proof_id == $vatNumberProof->_id;
            });

            foreach ($documents as $document) {
                $vatNumberProofFiles->push(
                    $document
                );
            }
        }

        return $this->collection($vatNumberProofFiles, new VatNumberProofFileTransformer);
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Item|null
     */
    public function includeCountryPlace(VatNumberProof $vatNumberProof): ?Item
    {
        $countryPlace = null;

        if ($vatNumberProof->relationLoaded('countryPlace')) {
            $countryPlace = $vatNumberProof->countryPlace;
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Item|null
     */
    public function includeAdmin(VatNumberProof $vatNumberProof): ?Item
    {
        $admin = $vatNumberProof->admin;

        return $admin ?
            $this->item(
                $admin,
                new AdminTransformer(
                    $this->adminAvatars
                )
            ) : null;
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Item|null
     */
    public function includeStatus(VatNumberProof $vatNumberProof): ?Item
    {
        $status = $vatNumberProof->getStatus();

        return $status ? $this->item($status, new VatNumberProofStatusTransformer) : null;
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Collection|null
     */
    public function includeExcludeTaxHistory(VatNumberProof $vatNumberProof): ?Collection
    {
        $excludeTaxHistory = null;

        if ($vatNumberProof->relationLoaded('excludeTaxHistory')) {
            $excludeTaxHistory = $vatNumberProof->excludeTaxHistory;
        }

        return $excludeTaxHistory ?
            $this->collection(
                $excludeTaxHistory,
                new ExcludeTaxHistoryTransformer(
                    $this->adminAvatars
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vat_number_proof';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vat_number_proofs';
    }
}

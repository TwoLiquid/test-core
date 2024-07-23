<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\VybeAppearanceCaseSupport;
use App\Models\MySql\AppearanceCase;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeAppearanceCaseSupportRepositoryInterface;
use Exception;

/**
 * Class VybeAppearanceCaseSupportRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeAppearanceCaseSupportRepository extends BaseRepository implements VybeAppearanceCaseSupportRepositoryInterface
{
    /**
     * VybeAppearanceCaseSupportRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeAppearanceCaseSupport.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeAppearanceCaseSupport|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeAppearanceCaseSupport
    {
        try {
            return VybeAppearanceCaseSupport::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeAppearanceCaseSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     * @param string $unitSuggestion
     * @param array|null $platformsIds
     *
     * @return VybeAppearanceCaseSupport|null
     *
     * @throws DatabaseException
     */
    public function store(
        AppearanceCase $appearanceCase,
        string $unitSuggestion,
        ?array $platformsIds = null
    ) : ?VybeAppearanceCaseSupport
    {
        try {
            return VybeAppearanceCaseSupport::query()->create([
                'appearance_case_id' => $appearanceCase->id,
                'unit_suggestion'    => $unitSuggestion,
                'platforms_ids'      => $platformsIds
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeAppearanceCaseSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
     * @param AppearanceCase|null $appearanceCase
     * @param string|null $unitSuggestion
     * @param array|null $platformsIds
     *
     * @return VybeAppearanceCaseSupport
     *
     * @throws DatabaseException
     */
    public function update(
        VybeAppearanceCaseSupport $vybeAppearanceCaseSupport,
        ?AppearanceCase $appearanceCase,
        ?string $unitSuggestion,
        ?array $platformsIds
    ) : VybeAppearanceCaseSupport
    {
        try {
            $vybeAppearanceCaseSupport->update([
                'appearance_id'   => $appearanceCase?->id,
                'unit_suggestion' => $unitSuggestion ?: $vybeAppearanceCaseSupport->unit_suggestion,
                'platforms_ids'   => $platformsIds ?: $vybeAppearanceCaseSupport->platforms_ids
            ]);

            return $vybeAppearanceCaseSupport;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeAppearanceCaseSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForAppearanceCase(
        AppearanceCase $appearanceCase
    ) : bool
    {
        try {
            return VybeAppearanceCaseSupport::query()
                ->where('appearance_case_id', '=', $appearanceCase->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeAppearanceCaseSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
    ) : bool
    {
        try {
            return $vybeAppearanceCaseSupport->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeAppearanceCaseSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}

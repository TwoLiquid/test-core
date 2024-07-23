<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\VybeSupport;
use App\Models\MySql\Category;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeSupportRepositoryInterface;
use Exception;

/**
 * Class VybeSupportRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeSupportRepository extends BaseRepository implements VybeSupportRepositoryInterface
{
    /**
     * VybeSupportRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeSupport.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeSupport|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeSupport
    {
        try {
            return VybeSupport::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param string|null $activitySuggestion
     * @param string|null $deviceSuggestion
     * @param array|null $devicesIds
     *
     * @return VybeSupport|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?string $activitySuggestion,
        ?string $deviceSuggestion,
        ?array $devicesIds
    ) : ?VybeSupport
    {
        try {
            return VybeSupport::query()->create([
                'vybe_id'                => $vybe->id,
                'category_id'            => $category?->id,
                'category_suggestion'    => $categorySuggestion,
                'subcategory_id'         => $subcategory?->id,
                'subcategory_suggestion' => $subcategorySuggestion,
                'activity_suggestion'    => $activitySuggestion,
                'device_suggestion'      => $deviceSuggestion,
                'devices_ids'            => $devicesIds
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeSupport $vybeSupport
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param string|null $activitySuggestion
     * @param string|null $deviceSuggestion
     * @param array|null $devicesIds
     *
     * @return VybeSupport
     *
     * @throws DatabaseException
     */
    public function update(
        VybeSupport $vybeSupport,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?string $activitySuggestion,
        ?string $deviceSuggestion,
        ?array $devicesIds
    ) : VybeSupport
    {
        try {
            $vybeSupport->update([
                'category_id'            => $category?->id,
                'category_suggestion'    => $categorySuggestion,
                'subcategory_id'         => $subcategory?->id,
                'subcategory_suggestion' => $subcategorySuggestion,
                'activity_suggestion'    => $activitySuggestion,
                'device_suggestion'      => $deviceSuggestion,
                'devices_ids'            => $devicesIds
            ]);

            return $vybeSupport;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVybe(
        Vybe $vybe
    ) : bool
    {
        try {
            return VybeSupport::query()
                ->where('vybe_id', '=', $vybe->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeSupport $vybeSupport
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeSupport $vybeSupport
    ) : bool
    {
        try {
            return $vybeSupport->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSupport.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}

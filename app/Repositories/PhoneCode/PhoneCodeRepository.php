<?php

namespace App\Repositories\PhoneCode;

use App\Exceptions\DatabaseException;
use App\Models\MySql\PhoneCode;
use App\Models\MySql\Place\CountryPlace;
use App\Repositories\BaseRepository;
use App\Repositories\PhoneCode\Interfaces\PhoneCodeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PhoneCodeRepository
 *
 * @package App\Repositories\PhoneCode
 */
class PhoneCodeRepository extends BaseRepository implements PhoneCodeRepositoryInterface
{
    /**
     * PhoneCodeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.phoneCode.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PhoneCode|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PhoneCode
    {
        try {
            return PhoneCode::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return PhoneCode::query()
                ->withCount([
                    'countryPlace'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return PhoneCode::query()
                ->withCount([
                    'countryPlace'
                ])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CountryPlace $countryPlace
     * @param string $code
     * @param bool $isDefault
     *
     * @return PhoneCode|null
     *
     * @throws DatabaseException
     */
    public function store(
        CountryPlace $countryPlace,
        string $code,
        bool $isDefault = false
    ) : ?PhoneCode
    {
        try {
            return PhoneCode::query()->create([
                'country_place_id' => $countryPlace->place_id,
                'code'             => trim($code),
                'is_default'       => $isDefault
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PhoneCode $phoneCode
     * @param CountryPlace|null $countryPlace
     * @param string|null $code
     * @param bool|null $isDefault
     *
     * @return PhoneCode
     *
     * @throws DatabaseException
     */
    public function update(
        PhoneCode $phoneCode,
        ?CountryPlace $countryPlace,
        ?string $code,
        ?bool $isDefault
    ) : PhoneCode
    {
        try {
            $phoneCode->update([
                'country_place_id' => $countryPlace?->place_id,
                'code'             => $code ? trim($code) : $phoneCode->code,
                'is_default'       => $isDefault ?: $phoneCode->is_default
            ]);

            return $phoneCode;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PhoneCode $phoneCode
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PhoneCode $phoneCode
    ) : bool
    {
        try {
            return $phoneCode->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/phoneCode.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}

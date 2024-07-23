<?php

namespace App\Repositories\Billing;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\BillingChangeRequestRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class BillingChangeRequestRepository
 *
 * @package App\Repositories\Billing
 */
class BillingChangeRequestRepository extends BaseRepository implements BillingChangeRequestRequestRepositoryInterface
{
    /**
     * BillingChangeRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.billingChangeRequest.cacheTime');
        $this->perPage = config('repositories.billingChangeRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?BillingChangeRequest
    {
        try {
            return BillingChangeRequest::find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?BillingChangeRequest
    {
        try {
            return BillingChangeRequest::query()
                ->with([
                    'user',
                    'countryPlace',
                    'previousCountryPlace',
                    'regionPlace'
                ])
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?BillingChangeRequest
    {
        try {
            return BillingChangeRequest::query()
                ->with([
                    'user',
                    'countryPlace',
                    'previousCountryPlace',
                    'regionPlace'
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $shown
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastShownForUser(
        User $user,
        bool $shown = true
    ) : ?BillingChangeRequest
    {
        try {
            return BillingChangeRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', $shown)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllCount() : int
    {
        try {
            return Cache::remember('billingChangeRequest.all.count', $this->cacheTime,
                function () {
                    return BillingChangeRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllBuyersCount() : int
    {
        try {
            return Cache::remember('billingChangeRequest.buyers.all.count', $this->cacheTime,
                function () {
                    return BillingChangeRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllSellersCount() : int
    {
        try {
            return Cache::remember('billingChangeRequest.sellers.all.count', $this->cacheTime,
                function () {
                    return BillingChangeRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllAffiliatesCount() : int
    {
        try {
            return Cache::remember('billingChangeRequest.affiliates.all.count', $this->cacheTime,
                function () {
                    return BillingChangeRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
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
            return BillingChangeRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return BillingChangeRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param string|null $oldCountry
     * @param string|null $newCountry
     * @param array|null $languagesIds
     * @param array|null $userBalanceTypesIds
     * @param array|null $userStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?string $id = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?string $oldCountry = null,
        ?string $newCountry = null,
        ?array $languagesIds = null,
        ?array $userBalanceTypesIds = null,
        ?array $userStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return BillingChangeRequest::query()
                ->with([
                    'user.balances',
                    'countryPlace',
                    'previousCountryPlace',
                    'admin'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('_id', '=', $id);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($oldCountry, function ($query) use ($oldCountry) {
                    $query->whereHas('previousCountry', function ($query) use ($oldCountry) {
                        $query->where('name', 'LIKE', '%'. $oldCountry . '%');
                    });
                })
                ->when($newCountry, function ($query) use ($newCountry) {
                    $query->whereHas('country', function ($query) use ($newCountry) {
                        $query->where('name', 'LIKE', '%'. $newCountry . '%');
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($userStatusesIds, function ($query) use ($userStatusesIds) {
                    $query->whereHas('user', function ($query) use ($userStatusesIds) {
                        $query->whereIn('account_status_id', $userStatusesIds);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function ($query) use ($admin) {
                        $query->where('last_name', 'LIKE', '%'. $admin . '%')
                            ->orWhere('first_name', 'LIKE', '%'. $admin . '%');
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('_id', $sortOrder);
                    }

                    if ($sortBy == 'created_at' || $sortBy == 'waiting') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'old_country_place') {
                        $query->orderBy('previous_country_place_id', $sortOrder);
                    }

                    if ($sortBy == 'country_place') {
                        $query->orderBy('country_place_id', $sortOrder);
                    }

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param string|null $oldCountry
     * @param string|null $newCountry
     * @param array|null $languagesIds
     * @param array|null $userBalanceTypesIds
     * @param array|null $userStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?string $id = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?string $oldCountry = null,
        ?string $newCountry = null,
        ?array $languagesIds = null,
        ?array $userBalanceTypesIds = null,
        ?array $userStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return BillingChangeRequest::query()
                ->with([
                    'user',
                    'countryPlace',
                    'previousCountryPlace',
                    'admin'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->whereIn('_id', $id);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($oldCountry, function ($query) use ($oldCountry) {
                    $query->whereHas('previous_country', function ($query) use ($oldCountry) {
                        $query->where('name', 'LIKE', '%'. $oldCountry . '%');
                    });
                })
                ->when($newCountry, function ($query) use ($newCountry) {
                    $query->whereHas('country', function ($query) use ($newCountry) {
                        $query->where('name', 'LIKE', '%'. $newCountry . '%');
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($userStatusesIds, function ($query) use ($userStatusesIds) {
                    $query->whereHas('user', function ($query) use ($userStatusesIds) {
                        $query->whereIn('account_status_id', $userStatusesIds);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function ($query) use ($admin) {
                        $query->where('last_name', 'LIKE', '%'. $admin . '%')
                            ->orWhere('first_name', 'LIKE', '%'. $admin . '%');
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('_id', $sortOrder);
                    }

                    if ($sortBy == 'created_at' || $sortBy == 'waiting') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'old_country_place') {
                        $query->orderBy('previous_country_place_id', $sortOrder);
                    }

                    if ($sortBy == 'country_place') {
                        $query->orderBy('country_place_id', $sortOrder);
                    }

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCount(
        RequestStatusListItem $requestStatusListItem
    ) : int
    {
        try {
            return BillingChangeRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCountByIds(
        array $ids,
        RequestStatusListItem $requestStatusListItem
    ) : int
    {
        try {
            return BillingChangeRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsPendingForUser(
        User $user
    ) : bool
    {
        try {
            return BillingChangeRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param CountryPlace $countryPlace
     * @param CountryPlace $previousCountryPlace
     * @param RegionPlace|null $regionPlace
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        CountryPlace $countryPlace,
        CountryPlace $previousCountryPlace,
        ?RegionPlace $regionPlace
    ) : ?BillingChangeRequest
    {
        try {
            return BillingChangeRequest::query()->create([
                'user_id'                   => $user->id,
                'country_place_id'          => $countryPlace->place_id,
                'country_place_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'previous_country_place_id' => $previousCountryPlace->place_id,
                'region_place_id'           => $regionPlace?->place_id,
                'request_status_id'         => RequestStatusList::getPendingItem()->id,
                'toast_message_type_id'     => ToastMessageTypeList::getSubmittedItem()->id,
                'toast_message_text'        => null,
                'shown'                     => false,
                'admin_id'                  => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param RequestFieldStatusListItem $countryPlaceStatus
     * @param string|null $toastMessageText
     *
     * @return BillingChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        BillingChangeRequest $billingChangeRequest,
        RequestFieldStatusListItem $countryPlaceStatus,
        ?string $toastMessageText
    ) : ?BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'country_place_status_id' => $countryPlaceStatus->id,
                'toast_message_text'      => $toastMessageText
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param bool $shown
     *
     * @return BillingChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        BillingChangeRequest $billingChangeRequest,
        bool $shown
    ) : BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'shown' => $shown
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return BillingChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        BillingChangeRequest $billingChangeRequest,
        RequestStatusListItem $requestStatusListItem
    ) : BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return BillingChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        BillingChangeRequest $billingChangeRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param LanguageListItem $languageListItem
     *
     * @return BillingChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        BillingChangeRequest $billingChangeRequest,
        LanguageListItem $languageListItem
    ) : BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     * @param Admin $admin
     *
     * @return BillingChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        BillingChangeRequest $billingChangeRequest,
        Admin $admin
    ) : BillingChangeRequest
    {
        try {
            $billingChangeRequest->update([
                'admin_id' => $admin->id
            ]);

            return $billingChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return BillingChangeRequest::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        BillingChangeRequest $billingChangeRequest
    ) : bool
    {
        try {
            return $billingChangeRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/billing/billingChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}

<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Currency\CurrencyList;
use App\Lists\Currency\CurrencyListItem;
use App\Lists\Gender\GenderListItem;
use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Lists\User\Label\UserLabelList;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Lists\User\Theme\UserThemeListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Payment\PaymentMethodField;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Timeslot;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class UserRepository
 *
 * @package App\Repositories\User
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.user.cacheTime');
        $this->perPage = config('repositories.user.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?User
    {
        try {
            return User::query()
                ->withCount([
                    'subscriptions',
                    'subscribers'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findByAuthId(
        ?int $id
    ) : ?User
    {
        try {
            return User::query()
                ->where('auth_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $username
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findByUsername(
        string $username
    ) : ?User
    {
        try {
            return User::query()
                ->with([
                    'languages',
                    'currentCityPlace',
                    'personalityTraits'
                ])
                ->withCount([
                    'subscribers',
                    'subscriptions'
                ])
                ->where('username', '=', $username)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $email
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findByEmail(
        string $email
    ) : ?User
    {
        try {
            return User::query()
                ->where('email', '=', trim($email))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findByIdForAdmin(
        ?int $id
    ) : ?User
    {
        try {
            return User::query()
                ->with([
                    'balances' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'status_id',
                            'amount',
                            'pending_amount'
                        ]);
                    },
                    'timezone.offsets',
                    'currentCityPlace' => function ($query) {
                        $query->with([
                            'timezone.offsets'
                        ]);
                    },
                    'referredUser',
                    'suspendAdmin',
                    'languages',
                    'personalityTraits'
                ])
                ->withCount([
                    'subscribers'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function findWithPaymentMethodField(
        PaymentMethodField $paymentMethodField,
        User $user
    ) : ?User
    {
        try {
            return $paymentMethodField->users()
                ->where('user_id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function findWithSubscriptions(
        User $user
    ) : User
    {
        try {
            return User::query()
                ->with([
                    'subscriptions' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'subscribers' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'balances',
                    'billing.countryPlace.taxRuleCountry',
                    'billing.regionPlace.taxRuleRegion'
                ])->where('id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
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
            return Cache::remember('users.all.count', $this->cacheTime,
                function () {
                    return User::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getBuyersCount() : int
    {
        try {
            return Cache::remember('users.buyers.all.count', $this->cacheTime,
                function () {
                    return User::query()
                        ->whereHas('balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getSellersCount() : int
    {
        try {
            return Cache::remember('users.sellers.all.count', $this->cacheTime,
                function () {
                    return User::query()
                        ->whereHas('balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAffiliatesCount() : int
    {
        try {
            return Cache::remember('user.affiliates.all.count', $this->cacheTime,
                function () {
                    return User::query()
                        ->whereHas('balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
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
            return User::query()
                ->withCount([
                    'subscribers'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllBySearch(
        string $search
    ) : Collection
    {
        try {
            return User::query()
                ->withCount([
                    'subscribers'
                ])
                ->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $userId
     * @param string|null $username
     * @param string|null $firstName
     * @param string|null $lastName
     * @param int|null $countryId
     * @param int|null $followers
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $statusesIds
     * @param int|null $userBalanceTypeId
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $userId = null,
        ?string $username = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?int $countryId = null,
        ?int $followers = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $statusesIds = null,
        ?int $userBalanceTypeId = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'state_status_id',
                    'account_status_id',
                    'username',
                    'created_at'
                ])
                ->withCount([
                    'subscriptions',
                    'subscribers'
                ])
                ->with([
                    'balances' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'status_id',
                            'amount',
                            'pending_amount'
                        ]);
                    }
                ])
                ->when($userId, function ($query) use ($userId) {
                    $query->where('id', '=', $userId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->where('username', 'LIKE', '%'. $username .'%');
                })
                ->when($followers, function ($query) use ($followers) {
                    $query->having('subscribers_count', '=', $followers);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereIn('account_status_id', $statusesIds);
                })
                ->when($userBalanceTypeId, function ($query) use ($userBalanceTypeId) {
                    $query->whereHas('balances', function($query) use ($userBalanceTypeId) {
                        $query->where('type_id', '=', $userBalanceTypeId);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'account_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'buyer_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'affiliate_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'username') {
                        $query->orderBy('username', $sortOrder);
                    }

                    if ($sortBy == 'followers_count') {
                        $query->orderBy('subscribers_count', $sortOrder);
                    }

                    if ($sortBy == 'created_time') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'created_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'account_status') {
                        $query->orderBy('account_status_id', $sortOrder);
                    }

                    if ($sortBy == 'buyer_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'affiliate_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $userId
     * @param string|null $username
     * @param string|null $firstName
     * @param string|null $lastName
     * @param int|null $countryId
     * @param int|null $followers
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $statusesIds
     * @param int|null $userBalanceTypeId
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
        ?int $userId = null,
        ?string $username = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?int $countryId = null,
        ?int $followers = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $statusesIds = null,
        ?int $userBalanceTypeId = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'state_status_id',
                    'account_status_id',
                    'username',
                    'created_at'
                ])
                ->withCount([
                    'subscriptions',
                    'subscribers'
                ])
                ->with([
                    'balances' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'status_id',
                            'amount',
                            'pending_amount'
                        ]);
                    }
                ])
                ->when($userId, function ($query) use ($userId) {
                    $query->where('id', '=', $userId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->where('username', 'LIKE', '%'. $username .'%');
                })
                ->when($followers, function ($query) use ($followers) {
                    $query->having('subscribers_count', '=', $followers);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereIn('account_status_id', $statusesIds);
                })
                ->when($userBalanceTypeId, function ($query) use ($userBalanceTypeId) {
                    $query->whereHas('balances', function($query) use ($userBalanceTypeId) {
                        $query->where('type_id', '=', $userBalanceTypeId);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'account_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'buyer_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'affiliate_id') {
                        $query->orderBy(
                            UserBalance::query()->select('id')
                                ->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'username') {
                        $query->orderBy('username', $sortOrder);
                    }

                    if ($sortBy == 'followers_count') {
                        $query->orderBy('subscribers_count', $sortOrder);
                    }

                    if ($sortBy == 'created_time') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'created_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'account_status') {
                        $query->orderBy('account_status_id', $sortOrder);
                    }

                    if ($sortBy == 'buyer_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'affiliate_status') {
                        $query->orderBy(
                            UserBalance::query()->select('status_id')
                                ->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->whereColumn('user_balances.user_id', 'users.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllExcept(
        User $user
    ) : Collection
    {
        try {
            return User::query()
                ->where('id', '!=', $user->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $userIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUsersByIds(
        ?array $userIds
    ) : Collection
    {
        try {
            return User::query()
                ->whereIn('id', $userIds ?: [])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $authIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUsersByAuthIds(
        ?array $authIds
    ) : Collection
    {
        try {
            return User::query()
                ->whereIn('auth_id', $authIds ?: [])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
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
            return User::query()
                ->withCount([
                    'subscribers',
                    'vybes'
                ])
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return User::query()
                ->withCount([
                    'subscribers',
                    'vybes'
                ])
                ->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $limit
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getWithGlobalSearchByLimit(
        string $search,
        ?int $limit
    ) : Collection
    {
        try {
            return User::query()
                ->withCount([
                    'vybes'
                ])
                ->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->when($limit, function ($query) use ($limit) {
                    $query->limit($limit);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getRecentVisits(
        User $user
    ) : Collection
    {
        try {
            return $user->visitedUsers()
                ->orderBy('visited_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $username
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function getUserProfileByUsername(
        string $username
    ) : ?User
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'gender_id',
                    'timezone_id',
                    'current_city_place_id',
                    'label_id',
                    'state_status_id',
                    'account_status_id',
                    'verification_status_id',
                    'username',
                    'email',
                    'birth_date',
                    'description',
                    'hide_gender',
                    'hide_age',
                    'hide_location',
                    'verified_celebrity',
                    'avatar_id',
                    'voice_sample_id',
                    'background_id',
                    'images_ids',
                    'videos_ids'
                ])
                ->withCount([
                    'subscriptions',
                    'subscribers'
                ])
                ->with([
                    'timezone.offsets',
                    'currentCityPlace' => function ($query) {
                        $query->with([
                            'timezone.offsets'
                        ]);
                    },
                    'balances' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'status_id',
                            'amount',
                            'pending_amount'
                        ]);
                    },
                    'subscriptions' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'subscribers' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'blockList' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'blocked_user_id',
                            'auth_id',
                            'username',
                            'avatar_id',
                            'voice_sample_id'
                        ]);
                    },
                    'youBlockedList' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'blocked_user_id',
                            'auth_id',
                            'username',
                            'avatar_id',
                            'voice_sample_id'
                        ]);
                    },
                    'personalityTraits' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'trait_id',
                            'votes'
                        ]);
                    },
                    'languages' => function ($query) {
                        $query->select([
                            'user_id',
                            'language_id',
                            'level_id'
                        ]);
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'activities.id',
                            'code',
                            'name'
                        ])->distinct('activities.id');
                    },
                    'vybes' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'status_id',
                            'activity_id',
                            'type_id',
                            'access_id',
                            'period_id',
                            'order_accept_id',
                            'title',
                            'rating',
                            'user_count',
                            'images_ids',
                            'videos_ids'
                        ])->with([
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'gender_id',
                                    'username',
                                    'avatar_id',
                                    'voice_sample_id'
                                ]);
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'category_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'category' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            },
                            'schedules' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            },
                            'appearanceCases' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                    'unit_id',
                                    'price',
                                    'description'
                                ])->with([
                                    'unit' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'name',
                                            'code',
                                            'duration',
                                            'visible'
                                        ]);
                                    },
                                    'platforms'
                                ]);
                            },
                            'devices' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            },
                            'orderItems' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'timeslot_id'
                                ])->with([
                                    'timeslot' => function ($query) {
                                        $query->withCount([
                                            'users'
                                        ]);
                                    }
                                ])->orderBy(
                                    'created_at',
                                    'desc'
                                )->limit(1);
                            }
                        ])->withCount([
                            'ratingVotes'
                        ])->whereIn(
                            'status_id', VybeStatusList::getAvailableForProfile()
                                ->pluck('id')
                                ->values()
                                ->toArray()
                        )->limit(
                            $this->perPage
                        );
                    },
                    'favoriteVybes' => function ($query) {
                        $query->select([
                            'id',
                            'favorite_vybes.user_id',
                            'favorite_vybes.vybe_id',
                            'activity_id',
                            'type_id',
                            'access_id',
                            'period_id',
                            'title',
                            'rating',
                            'user_count',
                            'images_ids',
                            'videos_ids'
                        ])->with([
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'gender_id',
                                    'username',
                                    'avatar_id',
                                    'voice_sample_id'
                                ]);
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'category_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'category' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            },
                            'schedules' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            },
                            'appearanceCases' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                    'unit_id',
                                    'price',
                                    'description'
                                ])->with([
                                    'unit' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'name',
                                            'code',
                                            'duration',
                                            'visible'
                                        ]);
                                    },
                                    'platforms' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            },
                            'devices' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            },
                            'orderItems' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'timeslot_id'
                                ])->with([
                                    'timeslot' => function ($query) {
                                        $query->withCount([
                                            'users'
                                        ]);
                                    }
                                ])->orderBy(
                                    'created_at',
                                    'desc'
                                )->limit(1);
                            }
                        ])->withCount([
                            'ratingVotes'
                        ])->whereIn(
                            'status_id', VybeStatusList::getAvailableForProfile()
                            ->pluck('id')
                            ->values()
                            ->toArray()
                        )->limit(
                            $this->perPage
                        );
                    }
                ])
                ->where('username', '=', $username)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function getAuthUser(
        User $user
    ) : ?User
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'username',
                    'avatar_id',
                    'background_id',
                    'account_status_id',
                    'state_status_id',
                    'theme_id',
                    'verification_status_id',
                    'email',
                    'birth_date',
                    'timezone_id',
                    'current_city_place_id',
                    'language_id',
                    'currency_id',
                    'suspend_reason',
                    'verify_blocked',
                    'temporary_deleted_at'
                ])
                ->withCount([
                    'cartItems',
                ])
                ->with([
                    'balances',
                    'timezone.offsets',
                    'currentCityPlace' => function ($query) {
                        $query->with([
                            'timezone.offsets'
                        ]);
                    },
                    'deactivationRequests',
                    'deletionRequests',
                    'unsuspendRequests'
                ])
                ->where('id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function getUserDashboardProfile(
        User $user
    ) : ?User
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'current_city_place_id',
                    'gender_id',
                    'username',
                    'birth_date',
                    'description',
                    'hide_gender',
                    'hide_age',
                    'hide_reviews',
                    'hide_location',
                    'top_vybers',
                    'avatar_id',
                    'background_id',
                    'voice_sample_id',
                    'images_ids',
                    'videos_ids'
                ])
                ->withCount([
                    'subscribers',
                    'subscriptions'
                ])
                ->with([
                    'timezone.offsets',
                    'currentCityPlace' => function ($query) {
                        $query->with([
                            'timezone.offsets'
                        ]);
                    },
                    'personalityTraits' => function ($query) use ($user) {
                        $query->select([
                            'id',
                            'user_id',
                            'trait_id',
                            'votes'
                        ]);
                    },
                    'languages' => function ($query) {
                        $query->select([
                            'user_id',
                            'language_id',
                            'level_id'
                        ]);
                    }
                ])
                ->where('id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getUserSubscriptionsIds(
        User $user
    ) : array
    {
        try {
            return $user->subscriptions
                ->pluck('id')
                ->toArray();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getUserSubscribersIds(
        User $user
    ) : array
    {
        try {
            return $user->subscribers
                ->pluck('id')
                ->toArray();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getUserSubscriptionsPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->subscriptions()
                ->with([
                    'blockList',
                    'billing.countryPlace'
                ])
                ->withCount([
                    'subscribers'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getUserSubscriptionsBySearchPaginated(
        User $user,
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->subscriptions()
                ->with([
                    'blockList',
                    'billing.countryPlace'
                ])
                ->withCount([
                    'subscribers'
                ])
                ->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getUserSubscribersPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->subscribers()
                ->with([
                    'blockList',
                    'billing.countryPlace'
                ])
                ->withCount([
                    'subscribers'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getUserSubscribersBySearchPaginated(
        User $user,
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->subscribers()
                ->with([
                    'blockList',
                    'billing.countryPlace'
                ])
                ->withCount([
                    'subscribers'
                ])
                ->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string|null $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getBlockedUsersBySearch(
        User $user,
        ?string $search = null
    ) : Collection
    {
        try {
            return $user->blockList()
                ->with([
                    'blockList'
                ])
//                ->whereHas('blockList', function ($query) use ($user) {
//                    $query->where('user_id', '=', $user->id);
//                })
                ->when($search, function ($query) use ( $search) {
                    $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%']);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getBlockedUsersPaginated(
        User $user,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->blockList()
                ->with([
                    'blockList'
                ])
//                ->whereHas('blockList', function ($query) use ($user) {
//                    $query->where('user_id', '=', $user->id);
//                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getBlockedUsersBySearchPaginated(
        User $user,
        string $search = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->blockList()
                ->with([
                    'blockList'
                ])
//                ->whereHas('blockList', function ($query) use ($user) {
//                    $query->where('user_id', '=', $user->id);
//                })
                ->when($search, function ($query) use ( $search) {
                    $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($search)) . '%']);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $authIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUserAccountStatusesByAuthIds(
        array $authIds
    ) : Collection
    {
        try {
            return User::query()
                ->select([
                    'auth_id',
                    'state_status_id',
                    'account_status_id'
                ])
                ->whereIn('auth_id', $authIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $activitiesIds
     * @param string|null $username
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivitiesIds(
        ?array $activitiesIds,
        ?string $username = null
    ) : Collection
    {
        try {
            return User::query()
                ->when($activitiesIds, function ($query) use ($activitiesIds, $username) {
                    $query->whereHas('activities', function ($query) use ($activitiesIds) {
                        $query->whereIn('activities.id', $activitiesIds);
                    })->when($username, function ($query) use ($username) {
                        $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($username)) . '%']);
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($username)) . '%']);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $activitiesIds
     * @param string|null $username
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getPaginatedByActivitiesIds(
        ?array $activitiesIds,
        ?string $username = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return User::query()
                ->when($activitiesIds, function ($query) use ($activitiesIds, $username) {
                    $query->whereHas('activities', function ($query) use ($activitiesIds) {
                        $query->whereIn('activities.id', $activitiesIds);
                    })->when($username, function ($query) use ($username) {
                        $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($username)) . '%']);
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereRaw('lower(username) like (?)', ['%' . strtolower(trim($username)) . '%']);
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getTopCreators() : Collection
    {
        try {
            return User::query()
                ->select([
                    'id',
                    'auth_id',
                    'avatar_id',
                    'background_id',
                    'voice_sample_id',
                    'images_ids',
                    'videos_ids',
                    'username',
                    'verification_status_id'
                ])
                ->whereHas('vybes', function ($query) {
                    $query->where('status_id', '=', VybeStatusList::getPublishedItem()->id);
                })
                ->withCount([
                    'subscribers',
                    'subscriptions'
                ])
                ->with([
                    'vybes' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'activity_id',
                            'type_id',
                            'access_id',
                            'period_id',
                            'status_id',
                            'title',
                            'rating',
                            'images_ids',
                            'videos_ids'
                        ])->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                            ->with([
                                'user' => function ($query) {
                                    $query->select([
                                        'id',
                                        'auth_id',
                                        'username'
                                    ]);
                                },
                                'activity' => function ($query) {
                                    $query->select([
                                        'id',
                                        'category_id',
                                        'name',
                                        'code'
                                    ]);
                                },
                                'schedules' => function ($query) {
                                    $query->select([
                                        'id',
                                        'vybe_id',
                                        'datetime_from',
                                        'datetime_to'
                                    ]);
                                },
                                'appearanceCases' => function ($query) {
                                    $query->select([
                                        'id',
                                        'vybe_id',
                                        'appearance_id',
                                        'unit_id',
                                        'price',
                                        'description'
                                    ])->with([
                                        'unit' => function ($query) {
                                            $query->select([
                                                'id',
                                                'type_id',
                                                'name',
                                                'code',
                                                'duration',
                                                'visible'
                                            ]);
                                        }
                                    ]);
                                }
                            ])
                            ->limit(8);
                    }
                ])
                ->limit(4)
                ->inRandomOrder()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param string|null $username
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForTimeslot(
        Timeslot $timeslot,
        ?string $username = null
    ) : Collection
    {
        try {
            return $timeslot->users()
                ->when($username, function ($query) use ($username) {
                    $query->where('username', 'LIKE', '%'. $username .'%');
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param string|null $username
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getForTimeslotPaginated(
        Timeslot $timeslot,
        ?string $username = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return $timeslot->users()
                ->when($username, function ($query) use ($username) {
                    $query->where('username', 'LIKE', '%'. $username .'%');
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $email
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkEmailExistence(
        string $email
    ) : bool
    {
        try {
            return User::query()
                ->where('email', '=', trim($email))
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $username
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkUsernameUniqueness(
        User $user,
        string $username
    ) : bool
    {
        try {
            return User::query()
                ->where('id', '!=', $user->id)
                ->where('username', '=', trim($username))
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $email
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkEmailUniqueness(
        User $user,
        string $email
    ) : bool
    {
        try {
            return User::query()
                ->where('id', '!=', $user->id)
                ->where('email', '=', trim($email))
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $following
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkFollowingExists(
        User $user,
        User $following
    ) : bool
    {
        try {
            return $user->subscriptions()
                ->where('subscription_id', '=', $following->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int $authId
     * @param string $username
     * @param string $email
     * @param GenderListItem|null $genderListItem
     * @param string|null $birthDate
     * @param bool|null $hideGender
     * @param bool|null $hideAge
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function store(
        int $authId,
        string $username,
        string $email,
        ?GenderListItem $genderListItem,
        ?string $birthDate,
        ?bool $hideGender,
        ?bool $hideAge
    ) : ?User
    {
        try {
            return User::query()->create([
                'auth_id'                => $authId,
                'username'               => trim($username),
                'email'                  => trim($email),
                'email_verify_token'     => md5(trim($email) . time()),
                'gender_id'              => $genderListItem?->id,
                'birth_date'             => $birthDate ? Carbon::parse($birthDate)->format('Y-m-d') : null,
                'language_id'            => LanguageList::getEnglish()->id,
                'currency_id'            => CurrencyList::getUsd()->id,
                'label_id'               => UserLabelList::getStreamer()->id,
                'state_status_id'        => UserStateStatusList::getOnline()->id,
                'account_status_id'      => AccountStatusList::getPending()->id,
                'verification_status_id' => UserIdVerificationStatusList::getUnverified()->id,
                'hide_gender'            => $hideGender ?: false,
                'hide_age'               => $hideAge ?: false,
                'verification_suspended' => false,
                'signed_up_at'           => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $visitedUser
     *
     * @throws DatabaseException
     */
    public function storeVisit(
        User $user,
        User $visitedUser
    ) : void
    {
        try {
            $user->visitedUsers()->sync([
                $visitedUser->id => [
                    'visited_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param string|null $username
     * @param string|null $birthdate
     * @param string|null $description
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateForProfileRequest(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?string $username,
        ?string $birthdate,
        ?string $description
    ) : User
    {
        try {
            $user->update([
                'account_status_id' => $accountStatusListItem ? $accountStatusListItem->id : $user->account_status_id,
                'username'          => $username ? trim($username) : $user->username,
                'birth_date'        => $birthdate ?: $user->birth_date,
                'description'       => $description ?: $user->description
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param GenderListItem|null $genderListItem
     * @param CityPlace|null $currentCityPlace
     * @param bool|null $hideGender
     * @param bool|null $hideAge
     * @param bool|null $hideLocation
     * @param bool|null $topVybers
     * @param bool|null $hideReviews
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateForDashboard(
        User $user,
        ?GenderListItem $genderListItem,
        ?CityPlace $currentCityPlace,
        ?bool $hideGender,
        ?bool $hideAge,
        ?bool $hideLocation,
        ?bool $topVybers,
        ?bool $hideReviews
    ) : User
    {
        try {
            $user->update([
                'gender_id'             => $genderListItem ? $genderListItem->id : $user->gender_id,
                'hide_gender'           => !is_null($hideGender) ? $hideGender : $user->hide_gender,
                'current_city_place_id' => $currentCityPlace ? $currentCityPlace->place_id : $user->current_city_place_id,
                'hide_age'              => !is_null($hideAge) ? $hideAge : $user->hide_age,
                'hide_location'         => !is_null($hideLocation) ? $hideLocation : $user->hide_location,
                'top_vybers'            => !is_null($topVybers) ? $topVybers : $user->top_vybers,
                'hide_reviews'          => !is_null($hideReviews) ? $hideReviews : $user->hide_reviews
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param LanguageListItem|null $languageListItem
     * @param CurrencyListItem|null $currencyListItem
     * @param Timezone|null $timezone
     * @param CityPlace|null $currentCityPlace
     * @param GenderListItem|null $genderListItem
     * @param string|null $username
     * @param string|null $email
     * @param bool|null $hideGender
     * @param string|null $birthDate
     * @param bool|null $hideAge
     * @param bool|null $verifiedPartner
     * @param bool|null $streamerBadge
     * @param bool|null $streamerMilestone
     * @param bool|null $hideLocation
     * @param string|null $description
     * @param bool|null $receiveNews
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateForAdmin(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?LanguageListItem $languageListItem,
        ?CurrencyListItem $currencyListItem,
        ?Timezone $timezone,
        ?CityPlace $currentCityPlace,
        ?GenderListItem $genderListItem,
        ?string $username,
        ?string $email,
        ?bool $hideGender,
        ?string $birthDate,
        ?bool $hideAge,
        ?bool $verifiedPartner,
        ?bool $streamerBadge,
        ?bool $streamerMilestone,
        ?bool $hideLocation,
        ?string $description,
        ?bool $receiveNews
    ) : User
    {
        try {
            $user->update([
                'account_status_id'      => $accountStatusListItem ? $accountStatusListItem->id : $user->account_status_id,
                'language_id'            => $languageListItem ? $languageListItem->id : $user->language_id,
                'currency_id'            => $currencyListItem ? $currencyListItem->id : $user->currency_id,
                'timezone_id'            => $timezone ? $timezone->id : $user->timezone_id,
                'current_city_place_id'  => $currentCityPlace ? $currentCityPlace->place_id : $user->current_city_place_id,
                'gender_id'              => $genderListItem ? $genderListItem->id : $user->gender_id,
                'username'               => $username ?: $user->username,
                'email'                  => $email ?: $user->email,
                'hide_gender'            => !is_null($hideGender) ? $hideGender : $user->hide_gender,
                'birth_date'             => !is_null($birthDate) ? Carbon::parse($birthDate)->format('Y-m-d') : $user->birth_date,
                'hide_age'               => !is_null($hideAge) ? $hideAge : $user->hide_age,
                'verified_partner'       => !is_null($verifiedPartner) ? $verifiedPartner : $user->verified_partner,
                'streamer_badge'         => !is_null($streamerBadge) ? $streamerBadge : $user->streamer_badge,
                'streamer_milestone'     => !is_null($streamerMilestone) ? $streamerMilestone : $user->streamer_milestone,
                'hide_location'          => !is_null($hideLocation) ? $hideLocation : $user->hide_location,
                'description'            => $description ?: $user->description,
                'receive_news'           => !is_null($receiveNews) ? $receiveNews : $user->receive_news
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $username
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateUsername(
        User $user,
        string $username
    ) : User
    {
        try {
            $user->update([
                'username' => trim($username)
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $email
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateEmail(
        User $user,
        string $email
    ) : User
    {
        try {
            $user->update([
                'email'                 => trim($email),
                'email_verified_at'     => null,
                'email_verify_token'    => md5(trim($email) . time()),
                'last_email_changed_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param UserStateStatusListItem $userStateStatusListItem
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateStateStatus(
        User $user,
        UserStateStatusListItem $userStateStatusListItem
    ) : User
    {
        try {
            $user->update([
                'state_status_id' => $userStateStatusListItem->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param UserThemeListItem $userThemeListItem
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateTheme(
        User $user,
        UserThemeListItem $userThemeListItem
    ) : User
    {
        try {
            $user->update([
                'theme_id' => $userThemeListItem->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AccountStatusListItem $accountStatusListItem
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateAccountStatus(
        User $user,
        AccountStatusListItem $accountStatusListItem
    ) : User
    {
        try {
            $user->update([
                'account_status_id' => $accountStatusListItem->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        User $user,
        LanguageListItem $languageListItem
    ) : User
    {
        try {
            $user->update([
                'language_id' => $languageListItem->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param CurrencyListItem $currencyListItem
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateCurrency(
        User $user,
        CurrencyListItem $currencyListItem
    ) : User
    {
        try {
            $user->update([
                'currency_id' => $currencyListItem->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string|null $suspendReason
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateSuspendInformation(
        User $user,
        ?string $suspendReason
    ) : User
    {
        try {
            $user->update([
                'suspend_reason' => $suspendReason
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $enableFastOrder
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateFastOrder(
        User $user,
        bool $enableFastOrder
    ) : User
    {
        try {
            $user->update([
                'enable_fast_order' => $enableFastOrder
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Timezone $timezone
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateTimezone(
        User $user,
        Timezone $timezone
    ) : User
    {
        try {
            $user->update([
                'timezone_id' => $timezone->id
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param UserIdVerificationStatusListItem $userIdVerificationStatusListItem
     * @param bool|null $verificationSuspended
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateVerification(
        User $user,
        UserIdVerificationStatusListItem $userIdVerificationStatusListItem,
        ?bool $verificationSuspended = false
    ) : User
    {
        try {
            $user->update([
                'verification_status_id' => $userIdVerificationStatusListItem->id,
                'verification_suspended' => !is_null($verificationSuspended) ? $verificationSuspended : $user->verification_suspended
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $loginAttemptsLeft
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateLoginAttempts(
        User $user,
        int $loginAttemptsLeft
    ) : User
    {
        try {
            $user->update([
                'login_attempts_left' => $loginAttemptsLeft
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $emailAttemptsLeft
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateEmailAttempts(
        User $user,
        int $emailAttemptsLeft
    ) : User
    {
        try {
            $user->update([
                'email_attempts_left' => $emailAttemptsLeft
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $passwordAttemptsLeft
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updatePasswordAttempts(
        User $user,
        int $passwordAttemptsLeft
    ) : User
    {
        try {
            $user->update([
                'password_attempts_left' => $passwordAttemptsLeft
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $avatarId
     * @param int|null $backgroundId
     * @param int|null $voiceSampleId
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function updateMediaIds(
        User $user,
        ?int $avatarId,
        ?int $backgroundId,
        ?int $voiceSampleId,
        ?array $imagesIds,
        ?array $videosIds
    ) : User
    {
        try {
            $user->update([
                'avatar_id'       => $avatarId ?: $user->avatar_id,
                'background_id'   => $backgroundId ?: $user->background_id,
                'voice_sample_id' => $voiceSampleId ?: $user->voice_sample_id,
                'images_ids'      => $imagesIds ?: $user->images_ids,
                'videos_ids'      => $videosIds ?: $user->videos_ids
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $avatarId
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setAvatarId(
        User $user,
        ?int $avatarId
    ) : User
    {
        try {
            $user->update([
                'avatar_id' => $avatarId
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $backgroundId
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setBackgroundId(
        User $user,
        ?int $backgroundId
    ) : User
    {
        try {
            $user->update([
                'background_id' => $backgroundId
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $voiceSampleId
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setVoiceSampleId(
        User $user,
        ?int $voiceSampleId
    ) : User
    {
        try {
            $user->update([
                'voice_sample_id' => $voiceSampleId
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array|null $imagesIds
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setImagesIds(
        User $user,
        ?array $imagesIds
    ) : User
    {
        try {
            $user->update([
                'images_ids' => $imagesIds
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array|null $videosIds
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setVideosIds(
        User $user,
        ?array $videosIds
    ) : User
    {
        try {
            $user->update([
                'videos_ids' => $videosIds
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function verifyEmail(
        User $user
    ) : User
    {
        try {
            $user->update([
                'email_verify_token' => null,
                'email_verified_at'  => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setEmailVerifyToken(
        User $user
    ) : User
    {
        try {
            $user->update([
                'email_verified_at'     => null,
                'email_verify_token'    => md5($user->email . time()),
                'last_email_changed_at' => null
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setPasswordResetToken(
        User $user
    ) : User
    {
        try {
            $user->update([
                'password_reset_token' => md5(trim($user->email) . time())
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function establishNextLoginAttempt(
        User $user
    ) : User
    {
        try {
            $user->update([
                'next_login_attempt_at' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function establishNextEmailAttempt(
        User $user
    ) : User
    {
        try {
            $user->update([
                'next_email_attempt_at' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function establishNextPasswordAttempt(
        User $user
    ) : User
    {
        try {
            $user->update([
                'next_password_attempt_at' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws DatabaseException
     */
    public function setTemporaryDeletedAt(
        User $user
    ) : User
    {
        try {
            $user->update([
                'temporary_deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $user;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $payoutMethod
     *
     * @throws DatabaseException
     */
    public function attachPayoutMethod(
        User $user,
        PaymentMethod $payoutMethod
    ) : void
    {
        try {
            $user->payoutMethods()->sync([
                $payoutMethod->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $payoutMethod
     *
     * @throws DatabaseException
     */
    public function detachPayoutMethod(
        User $user,
        PaymentMethod $payoutMethod
    ) : void
    {
        try {
            $user->payoutMethods()->detach([
                $payoutMethod->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $subscription
     *
     * @throws DatabaseException
     */
    public function attachSubscription(
        User $user,
        User $subscription
    ) : void
    {
        try {
            $user->subscriptions()->sync([
                $subscription->id => [
                    'added_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $subscription
     *
     * @throws DatabaseException
     */
    public function detachSubscription(
        User $user,
        User $subscription
    ) : void
    {
        try {
            $user->subscriptions()->detach([
                $subscription->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function attachFavoriteVybe(
        User $user,
        Vybe $vybe
    ) : void
    {
        try {
            $user->favoriteVybes()->sync([
                $vybe->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $favoriteVybesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachFavoriteVybes(
        User $user,
        array $favoriteVybesIds,
        ?bool $detaching
    ) : void
    {
        try {
            $user->favoriteVybes()->sync(
                $favoriteVybesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function detachFavoriteVybe(
        User $user,
        Vybe $vybe
    ) : void
    {
        try {
            $user->favoriteVybes()->detach([
                $vybe->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $favoriteVybesIds
     *
     * @throws DatabaseException
     */
    public function detachFavoriteVybes(
        User $user,
        array $favoriteVybesIds
    ) : void
    {
        try {
            $user->favoriteVybes()->detach(
                $favoriteVybesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function attachFavoriteActivity(
        User $user,
        Activity $activity
    ) : void
    {
        try {
            $user->favoriteActivities()->sync([
                $activity->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $favoriteActivitiesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachFavoriteActivities(
        User $user,
        array $favoriteActivitiesIds,
        ?bool $detaching
    ) : void
    {
        try {
            $user->favoriteActivities()->sync(
                $favoriteActivitiesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function detachFavoriteActivity(
        User $user,
        Activity $activity
    ) : void
    {
        try {
            $user->favoriteActivities()->detach([
                $activity->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $favoriteActivitiesIds
     *
     * @throws DatabaseException
     */
    public function detachFavoriteActivities(
        User $user,
        array $favoriteActivitiesIds
    ) : void
    {
        try {
            $user->favoriteActivities()->detach(
                $favoriteActivitiesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $subscriber
     *
     * @throws DatabaseException
     */
    public function attachSubscriber(
        User $user,
        User $subscriber
    ) : void
    {
        try {
            $user->subscribers()->sync([
                $subscriber->id => [
                    'added_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $subscriber
     *
     * @throws DatabaseException
     */
    public function detachSubscriber(
        User $user,
        User $subscriber
    ) : void
    {
        try {
            $user->subscribers()->detach([
                $subscriber->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $visitedUser
     *
     * @throws DatabaseException
     */
    public function attachVisitedUser(
        User $user,
        User $visitedUser
    ) : void
    {
        try  {
            $user->visitedUsers()->sync([
                $visitedUser->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $visitedUsersIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachVisitedUsers(
        User $user,
        array $visitedUsersIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $user->visitedUsers()->sync(
                $visitedUsersIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $visitedUser
     *
     * @throws DatabaseException
     */
    public function detachVisitedUser(
        User $user,
        User $visitedUser
    ) : void
    {
        try {
            $user->visitedUsers()->detach([
                $visitedUser->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array $visitedUsersIds
     *
     * @throws DatabaseException
     */
    public function detachVisitedUsers(
        User $user,
        array $visitedUsersIds
    ) : void
    {
        try {
            $user->visitedUsers()->detach(
                $visitedUsersIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $blockedUser
     *
     * @throws DatabaseException
     */
    public function attachBlockedUser(
        User $user,
        User $blockedUser
    ) : void
    {
        try {
            $user->blockList()->sync([
                $blockedUser->id => [
                    'blocked_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param User $blockedUser
     *
     * @throws DatabaseException
     */
    public function detachBlockedUser(
        User $user,
        User $blockedUser
    ) : void
    {
        try {
            $user->blockList()->detach([
                $blockedUser->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
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
    public function delete(
        User $user
    ) : bool
    {
        try {
            return $user->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
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
    public function forceDelete(
        User $user
    ) : bool
    {
        try {
            return $user->forceDelete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}

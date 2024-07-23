<?php

namespace App\Transformers\Api\General\Cart\Timeslot;

use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class TimeslotTransformer
 *
 * @package App\Transformers\Api\General\Cart\Timeslot
 */
class TimeslotTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * UserTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        User $user,
        EloquentCollection $userAvatars = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'users'
    ];

    /**
     * @param Timeslot $timeslot
     *
     * @return array
     */
    public function transform(Timeslot $timeslot) : array
    {
        return [
            'id'            => $timeslot->id,
            'datetime_from' => $timeslot->datetime_from ?
                Carbon::parse($timeslot->datetime_from)
                    ->addSeconds(
                        $this->authUser->timezone
                            ->getCurrentOffset()
                            ->offset
                    )
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $timeslot->datetime_to ?
                Carbon::parse($timeslot->datetime_to)
                    ->addSeconds(
                        $this->authUser->timezone
                            ->getCurrentOffset()
                            ->offset
                    )
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'users_actual'  => $timeslot->users_count
        ];
    }

    /**
     * @param Timeslot $timeslot
     *
     * @return Collection|null
     */
    public function includeUsers(Timeslot $timeslot) : ?Collection
    {
        $users = null;

        if ($timeslot->relationLoaded('users')) {
            $users = $timeslot->users;
        }

        return $users ?
            $this->collection(
                $users,
                new UserTransformer(
                    $this->authUser,
                    $this->userAvatars
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'timeslot';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'timeslots';
    }
}

<?php

namespace App\Transformers\Api\General\Dashboard\Purchase;

use App\Models\MySql\Timeslot;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class TimeslotTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase
 */
class TimeslotTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * UserTransformer constructor
     *
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        EloquentCollection $userAvatars = null
    )
    {
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
                $timeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $timeslot->datetime_to ?
                $timeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z') :
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

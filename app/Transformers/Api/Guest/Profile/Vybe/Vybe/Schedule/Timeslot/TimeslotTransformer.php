<?php

namespace App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot;

use App\Models\MySql\Timeslot;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class TimeslotTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot
 */
class TimeslotTransformer extends BaseTransformer
{
    /**
     * @var int|null
     */
    protected ?int $offset;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'users'
    ];

    /**
     * TimeslotTransformer constructor
     *
     * @param int|null $offset
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        ?int $offset = 0,
        ?EloquentCollection $userAvatars = null
    )
    {
        /** @var int offset */
        $this->offset = $offset;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;
    }

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
                    ->addSeconds($this->offset)
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $timeslot->datetime_to ?
                Carbon::parse($timeslot->datetime_to)
                    ->addSeconds($this->offset)
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'users_count'   => $timeslot->users_count ? $timeslot->users_count : null
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

        return $users ? $this->collection($users, new UserTransformer($this->userAvatars)) : null;
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

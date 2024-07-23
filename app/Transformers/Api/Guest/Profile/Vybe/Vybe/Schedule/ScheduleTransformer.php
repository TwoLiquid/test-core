<?php

namespace App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule;

use App\Models\MySql\Schedule;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Services\Timeslot\TimeslotService;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot\TimeslotTransformer;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class ScheduleTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule
 */
class ScheduleTransformer extends BaseTransformer
{
    /**
     * @var int|null
     */
    protected ?int $offset;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $timeslots;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * ScheduleTransformer constructor
     *
     * @param int|null $offset
     * @param EloquentCollection|null $timeslots
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        ?int $offset = 0,
        ?EloquentCollection $timeslots = null,
        ?EloquentCollection $userAvatars = null
    )
    {
        /** @var int offset */
        $this->offset = $offset;

        /** @var EloquentCollection timeslots */
        $this->timeslots = $timeslots;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'timeslots'
    ];

    /**
     * @param Schedule $schedule
     *
     * @return array
     */
    public function transform(Schedule $schedule) : array
    {
        return [
            'id'            => $schedule->id,
            'week_day'      => $schedule->datetime_from->englishDayOfWeek,
            'datetime_from' => $schedule->datetime_from ?
                Carbon::parse($schedule->datetime_from)
                    ->addSeconds($this->offset)
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $schedule->datetime_to ?
                Carbon::parse($schedule->datetime_to)
                    ->addSeconds($this->offset)
                    ->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param Schedule $schedule
     *
     * @return Collection|null
     */
    public function includeTimeslots(Schedule $schedule) : ?Collection
    {
        $timeslots = null;

        if ($this->timeslots->isNotEmpty()) {
            $timeslots = $this->timeslotService->getForSchedule(
                $schedule,
                $this->timeslots
            );
        }

        return $timeslots ?
            $this->collection(
                $timeslots,
                new TimeslotTransformer(
                    $this->offset,
                    $this->userAvatars
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'schedule';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'schedules';
    }
}

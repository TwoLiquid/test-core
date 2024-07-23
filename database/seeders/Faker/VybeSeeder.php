<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Unit\Type\UnitTypeList;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Sort\VybeSortListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Step\VybeStepList;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Schedule;
use App\Models\MySql\Unit;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeRatingVote;
use App\Repositories\User\UserRepository;
use App\Services\Vybe\VybeVersionService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Intervention\Image\Facades\Image;

/**
 * Class VybeSeeder
 *
 * @package Database\Seeders\Faker
 */
class VybeSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * Quantity of seeded vybes
     */
    protected const VYBE_PER_USER_QUANTITY = [
        'min' => 1,
        'max' => 7
    ];

    /**
     * Quantity of seeded vybes images
     */
    protected const VYBE_IMAGES_QUANTITY_MAX = 2;

    /**
     * Quantity of seeded vybes videos
     */
    protected const VYBE_VIDEOS_QUANTITY_MAX = 1;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var VybeVersionService
     */
    protected VybeVersionService $vybeVersionService;

    /**
     * VybeSeeder constructor
     *
     * @param string|null $amount
     * @param string|null $token
     *
     * @throws DatabaseException
     */
    public function __construct(
        ?string $amount = null,
        ?string $token = null
    )
    {
        /** @var string amount */
        $this->amount = $amount;

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice($token);

        /** @var VybeVersionService vybeVersionService */
        $this->vybeVersionService = new VybeVersionService();
    }

    /**
     * Run the database seeds
     *
     * @return void
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function run() : void
    {
        $vybes = new Collection();

        /** @var Collection $users */
        $users = app(UserRepository::class)->getAll();

        /** @var User $buyer */
        foreach ($users as $buyer) {

            for ($i = 0; $i < self::VYBE_PER_USER_QUANTITY[$this->amount ?: 'max']; $i++) {

                /** @var VybeStatusListItem $vybeStatusListItem */
                $vybeStatusListItem = VybeStatusList::getItem(
                    rand(1, 5)
                );

                /** @var VybeStepListItem $vybeStepListItem */
                $vybeStepListItem = VybeStepList::getCompleted();

                if ($vybeStatusListItem->isDraft()) {
                    $vybeStepListItem = VybeStepList::getItem(rand(1, 5));
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $activity = null;
                } else {

                    /** @var Activity $activity */
                    $activity = Activity::inRandomOrder()
                        ->limit(1)
                        ->first();
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybeTypeListItem = null;
                } else {

                    /** @var VybeSortListItem $vybeTypeListItem */
                    $vybeTypeListItem = VybeTypeList::getItem(
                        rand(1, 3)
                    );
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybePeriodListItem = null;
                } else {

                    /** @var VybePeriodListItem $vybePeriodListItem */
                    $vybePeriodListItem = VybePeriodList::getItem(
                        rand(1, 2)
                    );
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybeAccessListItem = null;
                } else {

                    /** @var VybeAccessListItem $vybeAccessListItem */
                    $vybeAccessListItem = VybeAccessList::getItem(
                        rand(1, 2)
                    );
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybeShowcaseListItem = null;
                } else {

                    /** @var VybeShowcaseListItem $vybeShowcaseListItem */
                    $vybeShowcaseListItem = VybeShowcaseList::getItem(
                        rand(1, 2)
                    );
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybeAgeLimitListItem = null;
                } else {

                    /** @var VybeAgeLimitListItem $vybeAgeLimitListItem */
                    $vybeAgeLimitListItem = VybeAgeLimitList::getItem(
                        rand(1, 3)
                    );
                }

                if ($vybeStatusListItem->isDraft() && !rand(0, 1)) {
                    $vybeOrderAcceptListItem = null;
                } else {

                    /** @var VybeOrderAcceptListItem $vybeOrderAcceptListItem */
                    $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem(
                        rand(1, 2)
                    );
                }

                $vybes->add(
                    Vybe::factory([
                        'user_id'         => $buyer->id,
                        'activity_id'     => $activity?->id,
                        'type_id'         => $vybeTypeListItem?->id,
                        'period_id'       => $vybePeriodListItem?->id,
                        'access_id'       => $vybeAccessListItem?->id,
                        'showcase_id'     => $vybeShowcaseListItem?->id,
                        'status_id'       => $vybeStatusListItem->id,
                        'age_limit_id'    => $vybeAgeLimitListItem?->id,
                        'order_accept_id' => $vybeOrderAcceptListItem?->id,
                        'step_id'         => $vybeStepListItem->id
                    ])->create()
                );
            }
        }

        /** @var Vybe $vybe */
        foreach ($vybes as $vybe) {

            if ($vybe->activity) {

                /**
                 * Checking activity category
                 */
                if ($vybe->activity
                        ->category
                        ->code == 'video-games'
                ) {
                    /**
                     * Attaching devices
                     */
                    $vybe->devices()->sync(
                        $vybe->activity
                            ->devices
                            ->pluck('id')
                            ->values()
                            ->toArray(),
                        false
                    );
                }
            }

            if ($vybe->getType()) {

                /**
                 * Attaching schedules
                 */
                if ($vybe->type_id != 3) {
                    for ($i = 0; $i < 7; $i++) {
                        if (rand(0, 1)) {
                            $periodsCount = rand(1, 4);

                            for ($j = 1; $j < $periodsCount + 1; $j++) {
                                $partOfADay = 24 / 4 * $j - rand(1, 6);

                                $date = Carbon::tomorrow()->endOfWeek(-$i);

                                $from = Carbon::create($date->year, $date->month, $date->day, $partOfADay);
                                $to = Carbon::create($date->year, $date->month, $date->day, $partOfADay)
                                    ->addHours(rand(1, 3));

                                Schedule::create([
                                    'vybe_id'       => $vybe->id,
                                    'datetime_from' => $from,
                                    'datetime_to'   => $to
                                ]);
                            }
                        }
                    }
                } else {
                    $startDateTime = getRandomDateTime();
                    $endDateTime = Carbon::parse($startDateTime)->addHours(rand(6, 24));

                    Schedule::create([
                        'vybe_id'       => $vybe->id,
                        'datetime_from' => $startDateTime,
                        'datetime_to'   => $endDateTime
                    ]);
                }
            }

            /** @var VybeAppearanceListItem $vybeAppearanceListItem */
            foreach (VybeAppearanceList::getItems() as $vybeAppearanceListItem) {

                if ($vybe->type_id == 3) {

                    /** @var Unit $unit */
                    $unit = Unit::inRandomOrder()
                        ->where('type_id', '=', UnitTypeList::getEvent()->id)
                        ->first();
                } else {

                    $unit = null;

                    if ($vybe->activity) {

                        /** @var Unit $unit */
                        $unit = $vybe->activity
                            ->units
                            ->random(1)
                            ->first();
                    }
                }

                if ($vybeAppearanceListItem->id == 3) {
                    AppearanceCase::create([
                        'vybe_id'       => $vybe->id,
                        'appearance_id' => $vybeAppearanceListItem->id,
                        'unit_id'       => $unit?->id,
                        'city_id'       => null,
                        'price'         => rand(100, 5000),
                        'same_location' => true
                    ]);
                } else {

                    /** @var AppearanceCase $appearanceCase */
                    $appearanceCase = AppearanceCase::create([
                        'vybe_id'       => $vybe->id,
                        'appearance_id' => $vybeAppearanceListItem->id,
                        'unit_id'       => $unit?->id,
                        'price'         => rand(100, 5000)
                    ]);

                    if ($vybe->activity) {
                        $appearanceCase->platforms()->sync(
                            $vybe->activity
                                ->platforms
                                ->pluck('id')
                                ->values()
                                ->toArray(),
                            false
                        );
                    }
                }
            }

            if (!$vybe->getStatus()->isDraft()) {

                /** @var Collection $votedUsers */
                $votedUsers = User::inRandomOrder()->limit(
                    rand(0, User::all()->count())
                )->get();

                $totalRatings = 0;

                /** @var User $votedUser */
                foreach ($votedUsers as $votedUser) {
                    VybeRatingVote::create([
                        'vybe_id' => $vybe->id,
                        'user_id' => $votedUser->id,
                        'rating'  => rand(0, 5)
                    ]);

                    $totalRatings = $totalRatings + rand(0, 5);
                }

                $vybe->rating = $votedUsers->count() != 0 ? round(
                    $totalRatings / $votedUsers->count(),
                    1
                ) : 0;

                $vybe->rating_votes = $votedUsers->count();
                $vybe->save();
            }

            $vybeImagesArray = [];

            /**
             * Attaching vybe images
             */
            $vybeImagesQuantity = rand(1, self::VYBE_IMAGES_QUANTITY_MAX);

            $main = true;
            for ($i = 0; $i < $vybeImagesQuantity; $i++) {

                $imageFileContents = file_get_contents(
                    resource_path('faker/vybe/images/image' . rand(1, 70) . '.jpeg')
                );

                $interventionImage = Image::make(
                    $imageFileContents
                );

                $vybeImagesArray[] = [
                    'content'   => base64_encode($imageFileContents),
                    'mime'      => $interventionImage->mime(),
                    'extension' => getImageExtensionFromMimeType($interventionImage->mime()),
                    'main'      => $main
                ];

                $main = false;
            }

            $vybeImagesResponse = $this->mediaMicroservice->storeVybeImages(
                $vybeImagesArray
            );

            $imagesIds = $vybeImagesResponse->images
                ->pluck('id')
                ->toArray();

            if (self::VYBE_VIDEOS_QUANTITY_MAX > 0) {
                $vybeVideosArray = [];

                /**
                 * Attaching vybe videos
                 */
                $vybeVideosQuantity = rand(1, self::VYBE_VIDEOS_QUANTITY_MAX);

                $main = true;
                for ($i = 0; $i < $vybeVideosQuantity; $i++) {

                    $videoFileContents = file_get_contents(
                        resource_path('faker/vybe/videos/video' . rand(1, 12) . '.mp4')
                    );

                    $vybeVideosArray[] = [
                        'content'   => base64_encode($videoFileContents),
                        'mime'      => 'video/mp4',
                        'extension' => 'mp4',
                        'main'      => $main
                    ];

                    $main = false;
                }

                $vybeVideosResponse = $this->mediaMicroservice->storeVybeVideos(
                    $vybeVideosArray
                );

                $videosIds = $vybeVideosResponse->videos
                    ->pluck('id')
                    ->toArray();

                $vybe->videos_ids = $videosIds;
            }

            $vybe->images_ids = $imagesIds;
            $vybe->save();

            if (!$vybe->getStatus()->isDraft()) {
                $this->vybeVersionService->create(
                    $vybe
                );
            }
        }

        $users = User::all();

        /** @var User $user */
        foreach ($users as $user) {
            $favoriteVybes = Vybe::inRandomOrder()
                ->where('status_id', '!=', VybeStatusList::getDraftItem()->id)
                ->limit(rand(0, Vybe::all()->count()))
                ->get();

            $user->favoriteVybes()->sync(
                $favoriteVybes->pluck('id')->toArray()
            );

            $favoriteActivities = Activity::inRandomOrder()
                ->limit(rand(0, Activity::all()->count()))
                ->get();

            $user->favoriteActivities()->sync(
                $favoriteActivities->pluck('id')->toArray()
            );
        }
    }
}

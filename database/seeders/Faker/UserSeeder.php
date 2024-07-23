<?php

namespace Database\Seeders\Faker;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Currency\CurrencyList;
use App\Lists\Gender\GenderList;
use App\Lists\Gender\GenderListItem;
use App\Lists\Language\LanguageList;
use App\Lists\Language\Level\LanguageLevelList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Label\UserLabelList;
use App\Lists\User\Label\UserLabelListItem;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Lists\User\State\Status\UserStateStatusListItem;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MySql\Billing;
use App\Models\MySql\Language;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\PersonalityTrait\PersonalityTraitVote;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use App\Repositories\IpRegistrationList\IpRegistrationListRepository;
use App\Repositories\User\UserRepository;
use App\Services\Alert\AlertService;
use App\Services\Timezone\TimezoneService;
use App\Services\User\UserBalanceService;
use App\Services\User\UserService;
use Carbon\Carbon;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use Intervention\Image\Facades\Image;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class UserSeeder
 *
 * @package Database\Seeders\Faker
 */
class UserSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * @var AlertService
     */
    protected AlertService $alertService;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var IpRegistrationListRepository
     */
    protected IpRegistrationListRepository $ipRegistrationListRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserSeeder constructor
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

        /** @var AlertService alertService */
        $this->alertService = new AlertService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var IpRegistrationListRepository ipRegistrationListRepository */
        $this->ipRegistrationListRepository = new IpRegistrationListRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice($token);

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
    }

    /**
     * Quantity of seeded users
     */
    protected const USER_QUANTITY = [
        'min' => 10,
        'max' => 50
    ];

    /**
     * Quantity of seeded users images
     */
    protected const USER_IMAGES_QUANTITY_MAX = 2;

    /**
     * Quantity of seeded users videos
     */
    protected const USER_VIDEOS_QUANTITY_MAX = 1;

    /**
     * Test users data
     */
    protected const TEST_USERS = [
        [
            'username' => 'Yevhenii',
            'email'    => 'ronson.kharkov@gmail.com'
        ],
        [
            'username' => 'Nikolai',
            'email'    => 'mailbk8@gmail.com'
        ]
    ];

    /**
     * Run the database seeds
     *
     * @return void
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws MicroserviceException
     * @throws TranslateException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_QUANTITY[$this->amount ?: 'max']; $i++) {
            $username = generateRandomString(10);

            $userResponse = $this->authMicroservice->registerUser(
                $username,
                $username . '@mello.com',
                'qwerty'
            );

            /** @var GenderListItem $gender */
            $gender = GenderList::getItem(
                rand(1, 3)
            );

            /** @var UserLabelListItem $userLabelListItem */
            $userLabelListItem = UserLabelList::getItem(
                rand(1, 5)
            );

            /** @var AccountStatusListItem $accountStatusListItem */
            $accountStatusListItem = AccountStatusList::getItem(
                rand(1, 5)
            );

            /** @var UserStateStatusListItem $stateStatusListItem */
            $userStateStatusListItem = UserStateStatusList::getItem(
                rand(1, 4)
            );

            User::factory([
                'auth_id'           => $userResponse->id,
                'gender_id'         => $gender->id,
                'language_id'       => LanguageList::getEnglish()->id,
                'currency_id'       => CurrencyList::getUsd()->id,
                'label_id'          => $userLabelListItem->id,
                'account_status_id' => $accountStatusListItem->id,
                'state_status_id'   => $userStateStatusListItem->id,
                'email'             => $username . '@mello.com'
            ])->create();
        }

        foreach (self::TEST_USERS as $testUser) {
            $userResponse = $this->authMicroservice->registerUser(
                $testUser['username'],
                $testUser['email'],
                'qwerty'
            );

            User::create([
                'auth_id'            => $userResponse->id,
                'gender_id'          => GenderList::getMale()->id,
                'language_id'        => LanguageList::getEnglish()->id,
                'currency_id'        => CurrencyList::getUsd()->id,
                'label_id'           => UserLabelList::getCreator()->id,
                'account_status_id'  => AccountStatusList::getPending()->id,
                'state_status_id'    => UserStateStatusList::getOnline()->id,
                'username'           => $testUser['username'],
                'email'              => $testUser['email'],
                'birth_date'         => Carbon::parse('1991-12-12')->format('Y-m-d H:i:s'),
                'description'        => null,
                'verified_celebrity' => false,
                'verify_blocked'     => false,
                'hide_gender'        => false,
                'hide_age'           => false,
                'hide_location'      => false,
                'verified_partner'   => false,
                'signed_up_at'       => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        /** @var User $user */
        foreach (User::all() as $user) {

            /** @var CountryPlace $countryPlace */
            $countryPlace = CountryPlace::query()
                ->where('excluded', '!=', true)
                ->inRandomOrder()
                ->first();

            $timezone = app(TimezoneService::class)->getByCoordinates(
                $countryPlace->latitude,
                $countryPlace->longitude
            );

            $user->timezone_id = $timezone->id;

            Billing::create([
                'user_id'          => $user->id,
                'country_place_id' => $countryPlace->place_id
            ]);

            $personalityListItems = PersonalityTraitList::getItems()->random(rand(1, 9));

            /** @var PersonalityTraitListItem $personalityListItem */
            foreach ($personalityListItems as $personalityListItem) {

                /** @var PersonalityTrait $personalityTrait */
                $personalityTrait = PersonalityTrait::create([
                    'user_id' => $user->id,
                    'trait_id' => $personalityListItem->id
                ]);

                $votedUsers = User::inRandomOrder()->limit(rand(0, User::all()->count()))->get();

                /** @var User $votedUser */
                foreach ($votedUsers as $votedUser) {
                    PersonalityTraitVote::create([
                        'personality_trait_id' => $personalityTrait->id,
                        'voter_id' => $votedUser->id
                    ]);

                    $personalityTrait->votes++;
                    $personalityTrait->save();
                }
            }

            $subscriptions = User::inRandomOrder()
                ->limit(rand(0, User::all()->count()))
                ->get();

            /** @var User $subscription */
            foreach ($subscriptions as $subscription) {
                $this->userRepository->attachSubscription(
                    $user,
                    $subscription
                );
            }

            $subscribers = User::inRandomOrder()
                ->limit(rand(0, User::all()->count()))
                ->get();

            /** @var User $subscriber */
            foreach ($subscribers as $subscriber) {
                $this->userRepository->attachSubscriber(
                    $user,
                    $subscriber
                );
            }

            $blockedUsers = User::inRandomOrder()
                ->limit(rand(0, 20))
                ->get();

            /** @var User $blockedUser */
            foreach ($blockedUsers as $blockedUser) {
                $this->userRepository->attachBlockedUser(
                    $user,
                    $blockedUser
                );
            }

            $languageListItem = LanguageList::getItem(
                rand(1, 17)
            );

            $languageLevelListItem = LanguageLevelList::getItem(
                rand(1, 4)
            );

            Language::create([
                'user_id' => $user->id,
                'language_id' => $languageListItem->id,
                'level_id' => $languageLevelListItem->id
            ]);

            if (!$user->balances()->first()) {
                $this->userService->createUserBalances(
                    $user,
                    UserBalanceStatusList::getActive()
                );
            }

            if (!$this->ipRegistrationListRepository->checkUserExistence(
                $user
            )) {

                /**
                 * Creating an ip registration list
                 */
                $this->ipRegistrationListRepository->store(
                    $user,
                    long2ip(rand(0, "4294967295")),
                    rand(0, 1)
                );
            }

            /**
             * Create default alerts
             */
            $this->alertService->createDefaultsToUser(
                $user
            );

            /**
             * Attaching user images
             */
            $userImagesQuantity = rand(1, self::USER_IMAGES_QUANTITY_MAX);
            for ($i = 0; $i < $userImagesQuantity; $i++) {
                $imageFileContents = file_get_contents(
                    resource_path('faker/user/album/images/image' . rand(1, 20) . '.jpg')
                );

                $interventionImage = Image::make(
                    $imageFileContents
                );

                $userImagesArray = [
                    [
                        'content' => base64_encode($imageFileContents),
                        'original_name' => generateFileName(),
                        'mime' => $interventionImage->mime(),
                        'extension' => getImageExtensionFromMimeType($interventionImage->mime())
                    ]
                ];

                $userImagesResponse = $this->mediaMicroservice->storeUserImages(
                    $user,
                    $userImagesArray
                );

                $imagesIds = $userImagesResponse->images
                    ->pluck('id')
                    ->toArray();

                $user->images_ids = $imagesIds;
            }

            /**
             * Attaching user videos
             */
            $userVideosQuantity = rand(1, self::USER_VIDEOS_QUANTITY_MAX);
            for ($i = 0; $i < $userVideosQuantity; $i++) {
                $videoFileContents = file_get_contents(
                    resource_path('faker/user/album/videos/video' . rand(1, 18) . '.mp4')
                );

                $userVideosArray = [
                    [
                        'content' => base64_encode($videoFileContents),
                        'original_name' => generateFileName(),
                        'mime' => 'video/mp4',
                        'extension' => 'mp4'
                    ]
                ];

                $userVideosResponse = $this->mediaMicroservice->storeUserVideos(
                    $user,
                    $userVideosArray
                );

                $videosIds = $userVideosResponse->videos
                    ->pluck('id')
                    ->toArray();

                $user->videos_ids = $videosIds;
            }

            /**
             * Attaching voice samples
             */
            $voiceSampleFileContents = file_get_contents(
                resource_path('faker/user/voice_samples/voice_sample' . rand(1, 8) . '.mp3')
            );

            $userVoiceSampleResponse = $this->mediaMicroservice->storeUserVoiceSample(
                $user,
                null,
                base64_encode($voiceSampleFileContents),
                'audio/mp3',
                'mp3'
            );

            $user->voice_sample_id = $userVoiceSampleResponse->id;

            /**
             * Attaching user avatars
             */
            $avatarFileContents = file_get_contents(
                resource_path('faker/user/avatars/avatar' . rand(1, 8) . '.png')
            );

            $interventionImage = Image::make(
                $avatarFileContents
            );

            $userAvatarResponse = $this->mediaMicroservice->storeUserAvatar(
                $user,
                null,
                base64_encode($avatarFileContents),
                $interventionImage->mime(),
                getImageExtensionFromMimeType($interventionImage->mime())
            );

            $user->avatar_id = $userAvatarResponse->id;

            /**
             * Attaching user backgrounds
             */

            $backgroundFileContents = file_get_contents(
                resource_path('faker/user/backgrounds/background' . rand(1, 8) . '.jpeg')
            );

            $interventionImage = Image::make(
                $backgroundFileContents
            );

            $userBackgroundResponse = $this->mediaMicroservice->storeUserBackground(
                $user,
                null,
                base64_encode($backgroundFileContents),
                $interventionImage->mime(),
                getImageExtensionFromMimeType($interventionImage->mime())
            );

            $user->background_id = $userBackgroundResponse->id;
            $user->save();

            app(UserBalanceService::class)->change(
                $user->getSellerBalance(),
                500000
            );
        }
    }
}

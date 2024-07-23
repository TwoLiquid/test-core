<?php

namespace App\Transformers\Api\Admin\User\User\Form;

use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Currency\CurrencyList;
use App\Lists\Gender\GenderList;
use App\Lists\Language\LanguageList;
use App\Lists\Language\Level\LanguageLevelList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UserFormTransformer
 *
 * @package App\Transformers\Api\Admin\User\User\Form
 */
class UserFormTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * UserFormTransformer constructor
     *
     * @param User $user
     */
    public function __construct(
        User $user
    )
    {
        /** @var User user */
        $this->user = $user;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'account_statuses',
        'user_balance_statuses',
        'languages',
        'language_levels',
        'currencies',
        'genders',
        'personality_traits',
        'request_field_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeAccountStatuses() : ?Collection
    {
        $accountStatuses = AccountStatusList::getItems();

        return $this->collection($accountStatuses, new AccountStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeUserBalanceStatuses() : ?Collection
    {
        $userBalanceStatuses = UserBalanceStatusList::getItems();

        return $this->collection($userBalanceStatuses, new UserBalanceStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getItems();

        return $this->collection($languages, new LanguageTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLanguageLevels() : ?Collection
    {
        $languageLevels = LanguageLevelList::getItems();

        return $this->collection($languageLevels, new LanguageLevelTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeCurrencies() : ?Collection
    {
        $currencies = CurrencyList::getItems();

        return $this->collection($currencies, new CurrencyTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeGenders() : ?Collection
    {
        $genders = GenderList::getItems();

        return $this->collection($genders, new GenderTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includePersonalityTraits() : ?Collection
    {
        $personalityTraits = PersonalityTraitList::getItems();

        return $this->collection($personalityTraits, new PersonalityTraitTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeRequestFieldStatuses() : ?Collection
    {
        $requestFieldStatuses = RequestFieldStatusList::getItems();

        return $this->collection($requestFieldStatuses, new RequestFieldStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}

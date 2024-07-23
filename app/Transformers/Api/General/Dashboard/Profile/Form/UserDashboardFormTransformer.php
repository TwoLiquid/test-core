<?php

namespace App\Transformers\Api\General\Dashboard\Profile\Form;

use App\Lists\Gender\GenderList;
use App\Lists\Language\LanguageList;
use App\Lists\Language\Level\LanguageLevelList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UserDashboardFormTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile\Form
 */
class UserDashboardFormTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * UserDashboardFormTransformer constructor
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
        'genders',
        'languages',
        'language_levels',
        'personality_traits'
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
    public function includeGenders() : ?Collection
    {
        $genders = GenderList::getItems();

        return $this->collection($genders, new GenderTransformer);
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
    public function includePersonalityTraits() : ?Collection
    {
        $personalityTraits = PersonalityTraitList::getItems();

        return $this->collection($personalityTraits, new PersonalityTraitTransformer);
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

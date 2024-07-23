<?php

namespace App\Transformers\Api\Guest\Navbar;

use App\Exceptions\DatabaseException;
use App\Lists\Currency\CurrencyList;
use App\Lists\Language\LanguageList;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Lists\User\Theme\UserThemeList;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Guest\Navbar
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * FormTransformer constructor
     *
     * @param EloquentCollection|null $categoryIcons
     */
    public function __construct(
        ?EloquentCollection $categoryIcons = null
    )
    {
        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'categories',
        'languages',
        'currencies',
        'timezones',
        'user_state_statuses',
        'user_themes'
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
     *
     * @throws DatabaseException
     */
    public function includeCategories() : ?Collection
    {
        $categories = $this->categoryRepository->getAll();

        return $this->collection(
            $categories,
            new CategoryTransformer(
                $this->categoryIcons
            )
        );
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageTransformer);
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
     *
     * @throws DatabaseException
     */
    public function includeTimezones() : ?Collection
    {
        $timezones = $this->timezoneRepository->getAll();

        return $this->collection($timezones, new TimezoneTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeUserStateStatuses() : ?Collection
    {
        $userStateStatuses = UserStateStatusList::getItems();

        return $this->collection($userStateStatuses, new UserStateStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeUserThemes() : ?Collection
    {
        $userThemes = UserThemeList::getItems();

        return $this->collection($userThemes, new UserThemeTransformer);
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

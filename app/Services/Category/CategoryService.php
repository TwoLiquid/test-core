<?php

namespace App\Services\Category;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Category;
use App\Repositories\Category\CategoryRepository;
use App\Services\Category\Interfaces\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoryService
 *
 * @package App\Services\Category
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * CategoryService constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();
    }

    /**
     * @param Category $category
     *
     * @return Collection
     */
    public function getCategoryWithSubcategories(
        Category $category
    ) : Collection
    {
        $categories = new Collection();

        $categories->push(
            $category
        );

        if ($category->relationLoaded('subcategories')) {

            /** @var Category $subcategory */
            foreach ($category->subcategories as $subcategory) {
                $categories->push(
                    $subcategory
                );
            }
        }

        return $categories;
    }

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function isVideoGames(
        Category $category
    ) : bool
    {
        return $category->code == 'video-games';
    }

    /**
     * @param array $categoryItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        array $categoryItems
    ) : Collection
    {
        $categories = new Collection();

        /** @var array $categoryItem */
        foreach ($categoryItems as $categoryItem) {

            /**
             * Getting category
             */
            $category = $this->categoryRepository->findFullForAdminById(
                $categoryItem['id']
            );

            /**
             * Checking category existence
             */
            if ($category) {

                /**
                 * Updating category position
                 */
                $this->categoryRepository->updatePosition(
                    $category,
                    $categoryItem['position']
                );

                /**
                 * Adding category to a collection
                 */
                $categories->add(
                    $category
                );
            }
        }

        return $categories;
    }
}

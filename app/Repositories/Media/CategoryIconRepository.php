<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Category;
use App\Models\MySql\Media\CategoryIcon;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\CategoryIconRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class CategoryIconRepository
 *
 * @package App\Repositories\Media
 */
class CategoryIconRepository extends BaseRepository implements CategoryIconRepositoryInterface
{
    /**
     * CategoryIconRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.categoryIcon.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return CategoryIcon|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?CategoryIcon
    {
        try {
            return CategoryIcon::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     *
     * @return CategoryIcon|null
     *
     * @throws DatabaseException
     */
    public function findByCategory(
        Category $category
    ) : ?CategoryIcon
    {
        try {
            return CategoryIcon::query()
                ->where('category_id', '=', $category->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return CategoryIcon::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return CategoryIcon::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $categories
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategories(
        Collection $categories
    ) : Collection
    {
        try {
            return CategoryIcon::query()
                ->whereIn('category_id', $categories->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return CategoryIcon::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/categoryIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
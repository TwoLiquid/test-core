<?php

namespace App\Http\Controllers\Api\Guest\Navbar;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Navbar\Interfaces\NavbarControllerInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Transformers\Api\Guest\Navbar\NavbarTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NavbarController
 *
 * @package App\Http\Controllers\Api\Guest\Navbar
 */
class NavbarController extends BaseController implements NavbarControllerInterface
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * NavbarController constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem([], new NavbarTransformer(
                $this->categoryIconRepository->getByCategories(
                    $this->categoryRepository->getParentCategories()
                )
            ))['navbar'],
            trans('validations/api/guest/navbar/index.result.success')
        );
    }
}

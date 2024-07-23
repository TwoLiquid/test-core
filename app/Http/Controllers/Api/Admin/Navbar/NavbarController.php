<?php

namespace App\Http\Controllers\Api\Admin\Navbar;

use App\Http\Controllers\Api\Admin\Navbar\Interfaces\NavbarControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Transformers\Api\Admin\Navbar\AdminNavbarTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NavbarController
 *
 * @package App\Http\Controllers\Api\Admin\General\Navbar
 */
class NavbarController extends BaseController implements NavbarControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([],
                new AdminNavbarTransformer
            ), trans('validations/api/admin/navbar/index.result.success')
        );
    }
}

<?php

namespace App\Http\Controllers\Api\Admin\User\Note;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Note\Interfaces\UserNoteControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Note\IndexRequest;
use App\Http\Requests\Api\Admin\User\Note\StoreRequest;
use App\Http\Requests\Api\Admin\User\Note\UpdateRequest;
use App\Repositories\Media\AdminAvatarRepository;
use App\Repositories\User\UserNoteRepository;
use App\Repositories\User\UserRepository;
use App\Services\Admin\AdminService;
use App\Services\Auth\AuthService;
use App\Transformers\Api\Admin\User\Note\UserNoteTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserNoteController
 *
 * @package App\Http\Controllers\Api\Admin\User\Note
 */
final class UserNoteController extends BaseController implements UserNoteControllerInterface
{
    /**
     * @var AdminAvatarRepository
     */
    protected AdminAvatarRepository $adminAvatarRepository;

    /**
     * @var AdminService
     */
    protected AdminService $adminService;

    /**
     * @var UserNoteRepository
     */
    protected UserNoteRepository $userNoteRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserNoteController constructor
     */
    public function __construct()
    {
        /** @var AdminAvatarRepository adminAvatarRepository */
        $this->adminAvatarRepository = new AdminAvatarRepository();

        /** @var AdminService adminService */
        $this->adminService = new AdminService();

        /** @var UserNoteRepository userNoteRepository */
        $this->userNoteRepository = new UserNoteRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/index.result.error.find')
            );
        }

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting paginated user notes
             */
            $userNotes = $this->userNoteRepository->getForUserPaginated(
                $user,
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($userNotes)->respondWithSuccess(
                $this->transformCollection($userNotes, new UserNoteTransformer(
                    $this->adminService->getByUserNotes(
                        new Collection($userNotes->items())
                    )
                )), trans('validations/api/admin/user/note/index.result.success')
            );
        }

        /**
         * Getting user notes
         */
        $userNotes = $this->userNoteRepository->getForUser(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($userNotes, new UserNoteTransformer(
                $this->adminService->getByUserNotes(
                    $userNotes
                )
            )), trans('validations/api/admin/user/note/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        int $id,
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/store.result.error.find')
            );
        }

        /**
         * Creating user note
         */
        $userNote = $this->userNoteRepository->store(
            $user,
            AuthService::admin(),
            $request->input('text')
        );

        /**
         * Checking user note existence
         */
        if (!$userNote) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/store.result.error.create')
            );
        }

        /**
         * Getting user notes
         */
        $userNotes = $this->userNoteRepository->getForUserPaginated(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($userNotes, new UserNoteTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByUserNotes(
                        new Collection($userNotes->items())
                    )
                )
            )), trans('validations/api/admin/user/note/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $userNoteId
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        int $userNoteId,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/update.result.error.find.user')
            );
        }

        /**
         * Getting user note
         */
        $userNote = $this->userNoteRepository->findById(
            $userNoteId
        );

        /**
         * Checking user existence
         */
        if (!$userNote) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/update.result.error.find.userNote')
            );
        }

        /**
         * Updating user note
         */
        $this->userNoteRepository->updateText(
            $userNote,
            $request->input('text')
        );

        /**
         * Getting user notes
         */
        $userNotes = $this->userNoteRepository->getForUserPaginated(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($userNotes, new UserNoteTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByUserNotes(
                        new Collection($userNotes->items())
                    )
                )
            )), trans('validations/api/admin/user/note/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $userNoteId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $userNoteId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/destroy.result.error.find.user')
            );
        }

        /**
         * Getting user note
         */
        $userNote = $this->userNoteRepository->findForUserById(
            $user,
            $userNoteId
        );

        /**
         * Checking user note existence
         */
        if (!$userNote) {
            return $this->respondWithError(
                trans('validations/api/admin/user/note/destroy.result.error.find.userNote')
            );
        }

        /**
         * Deleting user note
         */
        $this->userNoteRepository->delete(
            $userNote
        );

        /**
         * Getting user notes
         */
        $userNotes = $this->userNoteRepository->getForUserPaginated(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($userNotes, new UserNoteTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByUserNotes(
                        new Collection($userNotes->items())
                    )
                )
            )), trans('validations/api/admin/user/note/destroy.result.success')
        );
    }
}

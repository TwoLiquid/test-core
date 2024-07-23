<?php

namespace App\Transformers\Api\Admin\User\Note;

use App\Models\MySql\User\UserNote;
use App\Transformers\Api\Admin\User\Note\Admin\AdminTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserNoteTransformer
 *
 * @package App\Transformers\Api\Admin\User\Note
 */
class UserNoteTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $adminAvatars;

    /**
     * UserNoteTransformer constructor
     *
     * @param Collection|null $adminAvatars
     */
    public function __construct(
        Collection $adminAvatars = null
    )
    {
        /** @var Collection adminAvatars */
        $this->adminAvatars = $adminAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'admin'
    ];

    /**
     * @param UserNote $userNote
     *
     * @return array
     */
    public function transform(UserNote $userNote) : array
    {
        return [
            'id'   => $userNote->id,
            'text' => $userNote->text
        ];
    }

    /**
     * @param UserNote $userNote
     *
     * @return Item|null
     */
    public function includeAdmin(UserNote $userNote) : ?Item
    {
        $admin = null;

        if ($userNote->relationLoaded('admin')) {
            $admin = $userNote->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer($this->adminAvatars)) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_note';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_notes';
    }
}

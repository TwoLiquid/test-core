<?php

namespace App\Repositories\Alert\Interfaces;

use App\Lists\Alert\Align\AlertAlignListItem;
use App\Lists\Alert\Animation\AlertAnimationListItem;
use App\Lists\Alert\Cover\AlertCoverListItem;
use App\Lists\Alert\Logo\Align\AlertLogoAlignListItem;
use App\Lists\Alert\Text\Font\AlertTextFontListItem;
use App\Lists\Alert\Text\Style\AlertTextStyleListItem;
use App\Lists\Alert\Type\AlertTypeListItem;
use App\Models\MySql\Alert\Alert;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AlertRepositoryInterface
 *
 * @package App\Repositories\Alert\Interfaces
 */
interface AlertRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Alert|null
     */
    public function findById(
        ?int $id
    ) : ?Alert;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getAllForUser(
        User $user
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param AlertTypeListItem $alertTypeListItem
     * @param AlertAnimationListItem $alertAnimationListItem
     * @param AlertAlignListItem $alertAlignListItem
     * @param AlertTextFontListItem $alertTextFontListItem
     * @param AlertTextStyleListItem $alertTextStyleListItem
     * @param AlertLogoAlignListItem $alertLogoAlignListItem
     * @param AlertCoverListItem $alertCoverListItem
     * @param int $duration
     * @param string $textFontColor
     * @param int $textFontSize
     * @param string $logoColor
     * @param int $volume
     * @param string|null $username
     * @param float|null $amount
     * @param string|null $action
     * @param string|null $message
     * @param string|null $widgetUrl
     *
     * @return Alert|null
     */
    public function store(
        User $user,
        AlertTypeListItem $alertTypeListItem,
        AlertAnimationListItem $alertAnimationListItem,
        AlertAlignListItem $alertAlignListItem,
        AlertTextFontListItem $alertTextFontListItem,
        AlertTextStyleListItem $alertTextStyleListItem,
        AlertLogoAlignListItem $alertLogoAlignListItem,
        AlertCoverListItem $alertCoverListItem,
        int $duration,
        string $textFontColor,
        int $textFontSize,
        string $logoColor,
        int $volume,
        ?string $username,
        ?float $amount,
        ?string $action,
        ?string $message,
        ?string $widgetUrl
    ) : ?Alert;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Alert $alert
     * @param User|null $user
     * @param AlertTypeListItem|null $alertTypeListItem
     * @param AlertAnimationListItem|null $alertAnimationListItem
     * @param AlertAlignListItem|null $alertAlignListItem
     * @param AlertTextFontListItem|null $alertTextFontListItem
     * @param AlertTextStyleListItem|null $alertTextStyleListItem
     * @param AlertLogoAlignListItem|null $alertLogoAlignListItem
     * @param AlertCoverListItem|null $alertCoverListItem
     * @param int|null $duration
     * @param string|null $textFontColor
     * @param int|null $textFontSize
     * @param string|null $logoColor
     * @param int|null $volume
     * @param string|null $username
     * @param string|null $amount
     * @param string|null $action
     * @param string|null $message
     * @param string|null $widgetUrl
     *
     * @return Alert
     */
    public function update(
        Alert $alert,
        ?User $user,
        ?AlertTypeListItem $alertTypeListItem,
        ?AlertAnimationListItem $alertAnimationListItem,
        ?AlertAlignListItem $alertAlignListItem,
        ?AlertTextFontListItem $alertTextFontListItem,
        ?AlertTextStyleListItem $alertTextStyleListItem,
        ?AlertLogoAlignListItem $alertLogoAlignListItem,
        ?AlertCoverListItem $alertCoverListItem,
        ?int $duration,
        ?string $textFontColor,
        ?int $textFontSize,
        ?string $logoColor,
        ?int $volume,
        ?string $username,
        ?string $amount,
        ?string $action,
        ?string $message,
        ?string $widgetUrl
    ) : Alert;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Alert $alert
     *
     * @return bool
     */
    public function delete(
        Alert $alert
    ) : bool;
}

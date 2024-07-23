<?php

namespace App\Transformers\Api\Admin\Navbar;

use App\Exceptions\DatabaseException;
use App\Services\Admin\AdminNavbarService;
use App\Transformers\Api\Admin\Navbar\Section\ChatSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\InvoiceSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\OrderSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\RequestSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\ReviewSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\SuggestionSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\SupportSectionTransformer;
use App\Transformers\Api\Admin\Navbar\Section\UserSectionTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AdminNavbarTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar
 */
class AdminNavbarTransformer extends BaseTransformer
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * AdminNavbarTransformer constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'suggestions',
        'users',
        'requests',
        'orders',
        'invoices',
        'reviews',
        'chats',
        'support'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeSuggestions() : ?Item
    {
        $suggestions = $this->adminNavbarService->getSuggestions();

        return $suggestions ? $this->item($suggestions, new SuggestionSectionTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUsers() : ?Item
    {
        $users = $this->adminNavbarService->getUsers();

        return $users ? $this->item($users, new UserSectionTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeRequests() : ?Item
    {
        $requests = $this->adminNavbarService->getRequests();

        return $requests ? $this->item($requests, new RequestSectionTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeOrders() : ?Item
    {
        $orders = $this->adminNavbarService->getOrders();

        return $orders ? $this->item($orders, new OrderSectionTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeInvoices() : ?Item
    {
        $invoices = $this->adminNavbarService->getInvoices();

        return $invoices ? $this->item($invoices, new InvoiceSectionTransformer) : null;
    }

    /**
     * @return Item|null
     */
    public function includeReviews() : ?Item
    {
        $reviews = $this->adminNavbarService->getReviews();

        return $reviews ? $this->item($reviews, new ReviewSectionTransformer) : null;
    }

    /**
     * @return Item|null
     */
    public function includeChats() : ?Item
    {
        $chats = $this->adminNavbarService->getChat();

        return $chats ? $this->item($chats, new ChatSectionTransformer) : null;
    }

    /**
     * @return Item|null
     */
    public function includeSupport() : ?Item
    {
        $support = $this->adminNavbarService->getSupport();

        return $support ? $this->item($support, new SupportSectionTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'navbar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'navbars';
    }
}

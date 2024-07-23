<?php

namespace Database\Seeders\Faker;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\Receipt\AddFundsTransactionRepository;
use App\Repositories\User\UserRepository;
use App\Services\Log\LogService;
use App\Services\User\UserBalanceService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Exception;

/**
 * Class AddFundsReceiptSeeder
 *
 * @package Database\Seeders\Faker
 */
class AddFundsReceiptSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * Quantity of seeded add funds receipts
     */
    protected const ADD_FUNDS_RECEIPT_PER_USER_QUANTITY = [
        'min' => 5,
        'max' => 30
    ];

    /**
     * AddFundsReceiptSeeder constructor
     *
     * @param string|null $amount
     */
    public function __construct(
        ?string $amount = null
    )
    {
        /** @var string amount */
        $this->amount = $amount;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function run() : void
    {
        /** @var AddFundsReceiptRepository $addFundsReceiptRepository */
        $addFundsReceiptRepository = app(AddFundsReceiptRepository::class);

        /** @var AddFundsTransactionRepository $addFundsTransactionRepository */
        $addFundsTransactionRepository = app(AddFundsTransactionRepository::class);

        /** @var LogService $logService */
        $logService = app(LogService::class);

        /** @var Collection $users */
        $users = app(UserRepository::class)->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            for ($i = 0; $i < self::ADD_FUNDS_RECEIPT_PER_USER_QUANTITY[$this->amount ?: 'max']; $i++) {

                /** @var PaymentMethod $paymentMethod */
                $paymentMethod = PaymentMethod::all()
                    ->random(1)
                    ->first();

                $amount = rand(10000, 50000);
                $paymentFee = rand(10, 20);

                $addFundsReceipt = $addFundsReceiptRepository->store(
                    $user,
                    $paymentMethod,
                    AddFundsReceiptStatusList::getItem(rand(1, 4)),
                    null,
                    $amount,
                    $amount - $paymentFee,
                    $paymentFee
                );

                app(UserBalanceService::class)->change(
                    $user->getBuyerBalance(),
                    $amount - $paymentFee
                );

                try {

                    /**
                     * Creating add funds receipt log
                     */
                    $logService->addFundsReceiptLog(
                        $addFundsReceipt,
                        $addFundsReceipt->user->getBuyerBalance(),
                        UserBalanceTypeList::getBuyer(),
                        'created'
                    );
                } catch (Exception) {
                    // Ignore exception via faker case uselessness
                }

                if ($addFundsReceipt) {
                    $addFundsTransactionRepository->store(
                        $addFundsReceipt,
                        $paymentMethod,
                        null,
                        $amount,
                        rand(10, 20),
                        null
                    );

                    try {

                        /**
                         * Creating add funds receipt log
                         */
                        $logService->addFundsReceiptLog(
                            $addFundsReceipt,
                            $addFundsReceipt->user->getBuyerBalance(),
                            UserBalanceTypeList::getBuyer(),
                            'paid'
                        );
                    } catch (Exception) {
                        // Ignore exception via faker case uselessness
                    }
                }
            }
        }
    }
}

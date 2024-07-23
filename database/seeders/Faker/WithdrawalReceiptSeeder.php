<?php

namespace Database\Seeders\Faker;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Repositories\Receipt\WithdrawalTransactionRepository;
use App\Repositories\User\UserRepository;
use App\Services\Log\LogService;
use App\Services\User\UserBalanceService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Exception;

/**
 * Class WithdrawalReceiptSeeder
 *
 * @package Database\Seeders\Faker
 */
class WithdrawalReceiptSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * Quantity of seeded withdrawal receipts
     */
    protected const WITHDRAWAL_RECEIPT_PER_USER_QUANTITY = [
        'min' => 5,
        'max' => 30
    ];

    /**
     * WithdrawalReceiptSeeder constructor
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
        /** @var LogService $logService */
        $logService = app(LogService::class);

        /** @var WithdrawalRequestRepository $withdrawalRequestRepository */
        $withdrawalRequestRepository = app(WithdrawalRequestRepository::class);

        /** @var WithdrawalReceiptRepository $withdrawalReceiptRepository */
        $withdrawalReceiptRepository = app(WithdrawalReceiptRepository::class);

        /** @var WithdrawalTransactionRepository $withdrawalTransactionRepository */
        $withdrawalTransactionRepository = app(WithdrawalTransactionRepository::class);

        /** @var Collection $users */
        $users = app(UserRepository::class)->getAll();

        /** @var User $user */
        foreach ($users as $user) {
            for ($i = 0; $i < self::WITHDRAWAL_RECEIPT_PER_USER_QUANTITY[$this->amount ?: 'max']; $i++) {

                /** @var PaymentMethod $payoutMethod */
                $payoutMethod = PaymentMethod::all()
                    ->random(1)
                    ->first();

                $amount = rand(10, 50);
                $paymentFee = rand(5, 10);

                $withdrawalRequest = $withdrawalRequestRepository->store(
                    $user,
                    $payoutMethod,
                    $amount
                );

                $withdrawalRequest = $withdrawalRequestRepository->updateRequestStatus(
                    $withdrawalRequest,
                    RequestStatusList::getAcceptedItem()
                );

                $withdrawalReceipt = $withdrawalReceiptRepository->store(
                    $user,
                    $payoutMethod,
                    WithdrawalReceiptStatusList::getItem(rand(1, 3)),
                    null,
                    $amount,
                    $paymentFee,
                    $amount + $paymentFee
                );

                $withdrawalRequestRepository->updateReceipt(
                    $withdrawalRequest,
                    $withdrawalReceipt
                );

                try {

                    /**
                     * Creating withdrawal receipt log
                     */
                    $logService->addWithdrawalReceiptLog(
                        $withdrawalReceipt,
                        $withdrawalReceipt->user->getSellerBalance(),
                        UserBalanceTypeList::getSeller(),
                        'created'
                    );
                } catch (Exception) {
                    // Ignore exception via faker case uselessness
                }

                if ($withdrawalReceipt) {
                    $withdrawalTransactionRepository->store(
                        $withdrawalReceipt,
                        $payoutMethod,
                        null,
                        $amount,
                        rand(10, 20),
                        null
                    );

                    app(UserBalanceService::class)->change(
                        $user->getSellerBalance(),
                        $amount + $paymentFee,
                        true
                    );

                    try {

                        /**
                         * Creating withdrawal receipt log
                         */
                        $logService->addWithdrawalReceiptLog(
                            $withdrawalReceipt,
                            $withdrawalReceipt->user->getSellerBalance(),
                            UserBalanceTypeList::getSeller(),
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

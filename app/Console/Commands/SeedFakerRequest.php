<?php

namespace App\Console\Commands;

use App\Exceptions\DatabaseException;
use Database\Seeders\Faker\Request\Finance\BillingChangeRequestSeeder;
use Database\Seeders\Faker\Request\Finance\PayoutMethodRequestSeeder;
use Database\Seeders\Faker\Request\Finance\WithdrawalRequestSeeder;
use Database\Seeders\Faker\Request\User\UserDeactivationRequestSeeder;
use Database\Seeders\Faker\Request\User\UserDeletionRequestSeeder;
use Database\Seeders\Faker\Request\User\UserIdVerificationRequestSeeder;
use Database\Seeders\Faker\Request\User\UserProfileRequestSeeder;
use Database\Seeders\Faker\Request\User\UserUnsuspendRequestSeeder;
use Database\Seeders\Faker\Request\Vybe\VybeChangeRequestSeeder;
use Database\Seeders\Faker\Request\Vybe\VybeDeletionRequestSeeder;
use Database\Seeders\Faker\Request\Vybe\VybePublishRequestSeeder;
use Database\Seeders\Faker\Request\Vybe\VybeUnpublishRequestSeeder;
use Database\Seeders\Faker\Request\Vybe\VybeUnsuspendRequestSeeder;
use Illuminate\Console\Command;

/**
 * Class SeedFakerRequest
 *
 * @package App\Console\Commands
 */
class SeedFakerRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:faker-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws DatabaseException
     */
    public function handle() : void
    {
        set_time_limit(0);
        ini_set('memory_limit', '4G');
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');

        $this->info('Start faker user profile requests seeding...');
        $userProfileRequestSeeder = new UserProfileRequestSeeder();
        $userProfileRequestSeeder->run();
        $this->info('Faker user profile requests seeding completed!');

        $this->info('Start faker user id verification requests seeding...');
        $userIdVerificationRequestSeeder = new UserIdVerificationRequestSeeder();
        $userIdVerificationRequestSeeder->run();
        $this->info('Faker user id verification requests seeding completed!');

        $this->info('Start faker user deactivation requests seeding...');
        $userDeactivationRequestSeeder = new UserDeactivationRequestSeeder();
        $userDeactivationRequestSeeder->run();
        $this->info('Faker user deactivation requests seeding completed!');

        $this->info('Start faker user unsuspend requests seeding...');
        $userUnsuspendRequestSeeder = new UserUnsuspendRequestSeeder();
        $userUnsuspendRequestSeeder->run();
        $this->info('Faker user unsuspend requests seeding completed!');

        $this->info('Start faker user deletion requests seeding...');
        $userDeletionRequestSeeder = new UserDeletionRequestSeeder();
        $userDeletionRequestSeeder->run();
        $this->info('Faker user deletion requests seeding completed!');

        $this->info('Start faker vybe change requests seeding...');
        $vybeChangeRequestSeeder = new VybeChangeRequestSeeder();
        $vybeChangeRequestSeeder->run();
        $this->info('Faker vybe change requests seeding completed!');

        $this->info('Start faker vybe publish requests seeding...');
        $vybePublishRequestSeeder = new VybePublishRequestSeeder();
        $vybePublishRequestSeeder->run();
        $this->info('Faker vybe publish requests seeding completed!');

        $this->info('Start faker vybe unpublish requests seeding...');
        $vybeUnpublishRequestSeeder = new VybeUnpublishRequestSeeder();
        $vybeUnpublishRequestSeeder->run();
        $this->info('Faker vybe unpublish requests seeding completed!');

        $this->info('Start faker vybe unsuspend requests seeding...');
        $vybeUnsuspendRequestSeeder = new VybeUnsuspendRequestSeeder();
        $vybeUnsuspendRequestSeeder->run();
        $this->info('Faker vybe unsuspend requests seeding completed!');

        $this->info('Start faker vybe deletion requests seeding...');
        $vybeDeletionRequestSeeder = new VybeDeletionRequestSeeder();
        $vybeDeletionRequestSeeder->run();
        $this->info('Faker vybe deletion requests seeding completed!');

        $this->info('Start faker billing change requests seeding...');
        $billingChangeRequestSeeder = new BillingChangeRequestSeeder();
        $billingChangeRequestSeeder->run();
        $this->info('Faker billing change requests seeding completed!');

        $this->info('Start faker payout method requests seeding...');
        $payoutMethodRequestSeeder = new PayoutMethodRequestSeeder();
        $payoutMethodRequestSeeder->run();
        $this->info('Faker payout method requests seeding completed!');

        $this->info('Start faker withdrawal requests seeding...');
        $withdrawalRequestSeeder = new WithdrawalRequestSeeder();
        $withdrawalRequestSeeder->run();
        $this->info('Faker withdrawal requests seeding completed!');
    }
}

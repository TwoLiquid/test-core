<?php

namespace App\Console\Commands;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use Database\Seeders\Faker\ActivitySeeder;
use Database\Seeders\Faker\AddFundsReceiptSeeder;
use Database\Seeders\Faker\AdminSeeder;
use Database\Seeders\Faker\OrderSeeder;
use Database\Seeders\Faker\PaymentMethodSeeder;
use Database\Seeders\Faker\RegionPlaceSeeder;
use Database\Seeders\Faker\TaxRuleSeeder;
use Database\Seeders\Faker\TipSeeder;
use Database\Seeders\Faker\UnitSeeder;
use Database\Seeders\Faker\UserSeeder;
use Database\Seeders\Faker\VybeSeeder;
use Database\Seeders\Faker\WithdrawalReceiptSeeder;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JsonMapper_Exception;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class SeedFaker
 *
 * @package App\Console\Commands
 */
class SeedFaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:faker {--amount=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * System Bearer token
     *
     * @var string|null
     */
    protected ?string $token = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * Setting system token
         */
        $this->token = config('auth.system.token');

        parent::__construct();
    }

    /**
     * @return void
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     * @throws MicroserviceException
     * @throws TranslateException
     */
    public function handle() : void
    {
        set_time_limit(0);
        ini_set('memory_limit', '4G');
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');

        $this->info('Start faker region places seeding...');
        $regionPlaceSeeder = new RegionPlaceSeeder();
        $regionPlaceSeeder->run();
        $this->info('Faker region places seeding completed!');

        $this->info('Start faker tax rules seeding...');
        $taxRuleSeeder = new TaxRuleSeeder();
        $taxRuleSeeder->run();
        $this->info('Faker tax rules seeding completed!');

        $this->info('Start faker payment methods seeding...');
        $taxRuleSeeder = new PaymentMethodSeeder();
        $taxRuleSeeder->run();
        $this->info('Faker payment methods seeding completed!');

        $this->info('Start faker admins seeding...');
        $adminSeeder = new AdminSeeder();
        $adminSeeder->run();
        $this->info('Faker admins seeding completed!');

        $this->info('Start faker users seeding...');
        $userSeeder = new UserSeeder($this->option('amount'), $this->token);
        $userSeeder->run();
        $this->info('Faker users seeding completed!');

        $this->info('Start faker units seeding...');
        $unitSeeder = new UnitSeeder();
        $unitSeeder->run();
        $this->info('Faker units seeding completed!');

        $this->info('Start faker activities seeding...');
        $activitySeeder = new ActivitySeeder($this->token);
        $activitySeeder->run();
        $this->info('Faker activities seeding completed!');

        $this->info('Start faker vybes seeding...');
        $vybeSeeder = new VybeSeeder($this->option('amount'), $this->token);
        $vybeSeeder->run();
        $this->info('Faker vybes seeding completed!');

        $this->info('Start faker add funds receipts seeding...');
        $addFundsReceiptSeeder = new AddFundsReceiptSeeder($this->option('amount'));
        $addFundsReceiptSeeder->run();
        $this->info('Faker add funds receipts seeding completed!');

        $this->info('Start faker orders seeding...');
        $orderSeeder = new OrderSeeder($this->option('amount'));
        $orderSeeder->run();
        $this->info('Faker orders seeding completed!');

        $this->info('Start faker withdrawal receipts seeding...');
        $withdrawalReceiptSeeder = new WithdrawalReceiptSeeder($this->option('amount'));
        $withdrawalReceiptSeeder->run();
        $this->info('Faker withdrawal receipts seeding completed!');

        $this->info('Start faker tips seeding...');
        $tipSeeder = new TipSeeder($this->option('amount'));
        $tipSeeder->run();
        $this->info('Faker tips seeding completed!');
    }
}

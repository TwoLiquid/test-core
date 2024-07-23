<?php

namespace Database\Seeders;

use App\Lists\Admin\Status\AdminStatusList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Admin\AdminRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use Exception;

/**
 * Class AdminSeeder
 *
 * @package Database\Seeders
 */
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function run() : void
    {
        $authMicroservice = app(AuthMicroservice::class);
        $adminRepository = app(AdminRepository::class);

        try {

            /**
             * Creating user in gateway
             */
            $userResponse = $authMicroservice->registerAdmin(
                config('admin.root.email'),
                config('admin.root.password')
            );

            /**
             * Creating admin
             */
            $adminRepository->store(
                $userResponse->id,
                AdminStatusList::getActive(),
                config('admin.root.first_name'),
                config('admin.root.last_name'),
                config('admin.root.email'),
                true
            );
        } catch (Exception) {
            $this->command->error(
                'Failed to seed administrator.'
            );
        }
    }
}

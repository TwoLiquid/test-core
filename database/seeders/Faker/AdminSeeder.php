<?php

namespace Database\Seeders\Faker;

use App\Lists\Admin\Status\AdminStatusList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Admin\AdminRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use Exception;

/**
 * Class AdminSeeder
 *
 * @package Database\Seeders\Faker
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

        $admins = [
            [
                'first_name' => 'Admin',
                'last_name'  => 'Test',
                'email'      => 'test@test.com',
            ],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Denis',
                'email'      => 'breeh2004@live.com',
            ],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Yevhenii',
                'email'      => 'ronson.kharkov@googlemail.com',
            ],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Nikolai',
                'email'      => 'mailbk8@googlemail.com',
            ]
        ];

        /** @var array $admin */
        foreach ($admins as $admin) {

            try {

                /**
                 * Creating user in gateway
                 */
                $userResponse = $authMicroservice->registerAdmin(
                    $admin['email'],
                    config('admin.root.password')
                );

                /**
                 * Creating admin
                 */
                $adminRepository->store(
                    $userResponse->id,
                    AdminStatusList::getActive(),
                    $admin['first_name'],
                    $admin['last_name'],
                    $admin['email'],
                    true
                );
            } catch (Exception) {
                $this->command->error(
                    'Failed to seed faker administrator.'
                );
            }
        }
    }
}

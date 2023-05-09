<?php

namespace W360\SecureData\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use W360\SecureData\SecureDataServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

abstract class TestCase extends BaseTestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(EloquentFactory::class)->load(__DIR__ . "/../database/factories");
    }


    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     *
     * @throws \Mockery\Exception\InvalidCountException
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }


    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            SecureDataServiceProvider::class
        ];
    }


    /**
     * @param Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        // run the up() method of that migration class
        (new \CreateUsersTable)->up();

        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../database/migrations/create_admins_table.php.stub';
        // run the up() method of that migration class
        (new \CreateAdminsTable)->up();

        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../database/migrations/create_webs_table.php.stub';
        // run the up() method of that migration class
        (new \CreateWebsTable)->up();
    }


}
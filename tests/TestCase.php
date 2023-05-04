<?php

namespace W360\SecureData\Tests;

use Illuminate\Support\Facades\DB;
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
        $this->artisan('migrate');
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
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            SecureDataServiceProvider::class
        ];
    }


}
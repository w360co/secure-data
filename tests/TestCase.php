<?php

namespace W360\ImageStorage\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use W360\ImageStorage\ImageStorageServiceProvider;

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

        $this->withFactories(__DIR__."/../database/factories");
    }


    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
           ImageStorageServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $app->useStoragePath(__DIR__ . '/../storage/');

    }

}
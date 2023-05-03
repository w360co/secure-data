<?php

namespace W360\SecureData\Tests;

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
        //$this->withFactories(__DIR__."/../database/factories");
        $this->app
            ->make(EloquentFactory::class)
            ->load(__DIR__."/../database/factories");
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
           SecureDataServiceProvider::class
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
       $app['config']->set('database.default', 'mysql');
       $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost'
       ]);

    }

}
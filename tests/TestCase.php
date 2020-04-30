<?php

namespace Techneved\Admin\Login\Tests;

use \Orchestra\Testbench\TestCase as BaseTestCase;

use Techneved\Admin\Login\AdminLoginServiceProvider;
use Techneved\Admin\Login\Models\Admin;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * setUp
     * 
     * @return void
     */
    function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/../database/factories');
        app()->register(LaravelServiceProvider::class);
    }
    /*
    * Get package providers.
    *
    * @param \Illuminate\Foundation\Application  $app
    *
    * @return array
    */

    protected function getPackageProviders($app)
    {
        return [
            AdminLoginServiceProvider::class
        ];
    } 
    
     /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('jwt.secret', 'abcdef');
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver'    => 'sqlite',
            'database'  => ':memory:'
        ]);
    }

    /** Factories Tables */
    public function createAdmin($args = [], $num = null)
    {
        return factory(Admin::class, $num)->create($args);
    }
}

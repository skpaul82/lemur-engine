<?php

namespace Tests\Setup;

use Tests\TestCase;

/**
 * This is a kind of fake Test
 * so that we can set up the db before running ANY tests
 *
 * Class SetupPoliciesTest
 * @package Tests\Policies
 */
class SetupDBTest extends TestCase
{

    protected static $initialized = false;

    /**
     * make sure you are using the test database...
     * this will drop which ever db is specified in the testing section
     */
    public function setUp() :void
    {

        parent::setUp();

        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        $this->artisan('key:generate ');

        if (!self::$initialized) {
            // Do something once here for _all_ test subclasses.
            self::$initialized = true;
            $this->artisan('migrate:fresh');
            $this->artisan('db:seed --class=RolesTableSeeder');
            $this->artisan('db:seed --class=UsersTableSeeder');
            $this->artisan('db:seed --class=LanguagesTableSeeder');
            $this->artisan('db:seed --class=BotsTableSeeder');
            $this->artisan('db:seed --class=BotPropertiesTableSeeder');
            $this->artisan('db:seed --class=UnitTestCategoriesTableSeeder');
            $this->artisan('db:seed --class=WordTransformationsTableSeeder');
        }
    }

    public function testMakeSureRolesCreated()
    {
        $this->assertDatabaseHas('roles', ['name'=>'admin','guard_name'=>'web']);
        $this->assertDatabaseHas('roles', ['name'=>'admin','guard_name'=>'api']);
        $this->assertDatabaseHas('roles', ['name'=>'author','guard_name'=>'web']);
        $this->assertDatabaseHas('roles', ['name'=>'author','guard_name'=>'api']);
    }


    /**
     *
     */
    public function tearDown() :void
    {

        $config = app('config');
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        //$this->artisan('optimize');
        parent::tearDown();
        app()->instance('config', $config);
    }
}

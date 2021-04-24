<?php
namespace Tests;

use Illuminate\Support\Facades\Artisan;

trait MigrateFreshSeedOnce
{
    /**
     * If true, setup has run at least once.
     * @var boolean
     */
    protected static $setUpHasRunOnce = false;
    /**
     * After the first run of setUp "migrate:fresh --seed"
     * @return void
     */
    public function setUp()
    {
        dd(1);
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            $this->artisan('config:clear');
            $this->artisan('cache:clear');
            $this->artisan('route:clear');

            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed',
                ['--class' => 'DatabaseSeeder']
            );
            static::$setUpHasRunOnce = true;
        }
    }
}

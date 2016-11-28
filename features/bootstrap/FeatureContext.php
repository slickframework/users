<?php

use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected static $options = [
        'driver' => \Slick\Database\Adapter::DRIVER_MYSQL,
        'options' => [
            'host' => 'localhost',
            'database' => 'slick_users',
            'username' => 'root',
            'password' => '',
        ]
    ];

    /**
     * @var \Slick\Database\Adapter\AdapterInterface
     */
    protected static $adapter;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeSuite
     */
    public static function setup(BeforeSuiteScope $scope)
    {
        $host = getenv('DOCKER_HOST')
            ? getenv('DOCKER_HOST')
            : 'db';
        static::$options['options']['host'] = $host;
        $sql = file_get_contents(__DIR__.'/db-dump.sql');
        $adapter = new \Slick\Database\Adapter(static::$options);
        static::$adapter = $adapter->initialize();
        static::$adapter->execute($sql);
    }

    /** @AfterSuite */
    public static function tearDown(AfterSuiteScope $scope)
    {
        $sql = file_get_contents(__DIR__.'/db-dump.sql');
        static::$adapter->execute($sql);
    }
}

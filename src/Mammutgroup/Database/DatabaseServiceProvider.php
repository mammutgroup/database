<?php

namespace Mammutgroup\Database;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider as IlluminateServiceProvider;
use Mammutgroup\Database\Connectors\ConnectionFactory;

/**
 * Class DatabaseServiceProvider
 * @package Mammutgroup\Database
 */
class DatabaseServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
    }
}

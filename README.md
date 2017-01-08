[![Build Status](https://travis-ci.org/Mammutgroup/Database.svg?branch=master)](https://travis-ci.org/Mammutgroup/Database)


Laravel-Mysql-Extended
=========================

An extended PostgreSQL driver for Laravel 5 with support for some aditional PostgreSQL data types: hstore, uuid, geometric types (point, path, circle, line, polygon...)

## Getting Started  
### Laravel 5.2
1. Run `composer require Mammutgroup/database` in your project root directory.
2. Add `Mammutgroup\Database\DatabaseServiceProvider::class` to `config/app.php`'s `providers` array.

Then you are done.

### Lumen 5.*
1. Run `composer require Mammutgroup/database` in your project root directory.
2. Add `$app->register(Mammutgroup\Database\DatabaseServiceProvider::class);` to `bootstrap/app.php` (under the "Register Service Providers" section)
3. Uncomment the line `$app->withEloquent();` in `bootstrap/app.php`

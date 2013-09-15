<?php

use Silex\Provider\MonologServiceProvider;

// include the base configuration
require __DIR__.'/base.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'equinox',
        'user'      => 'root',
        'password'  => '1q2w3e',
        'charset'   => 'utf8',
    ),
));
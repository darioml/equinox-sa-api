<?php

$app->before(function (Request $request) {
    if (!in_array($request->server->get("REMOTE_ADDR"), array('127.0.0.1'))) {
        echo "Wrong IP";
        $app->abort();
    }
});

// configure your app for the production environment
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'stateless' => true,
            'pattern' => '^/',
            'http' => true,
            'users' => array(
                '--' => array('ROLE_ADMIN', '--'),
            ),
        ),
    )
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'equinox',
        'user'      => 'user',
        'password'  => 'password',
        'charset'   => 'utf8',
    ),
));

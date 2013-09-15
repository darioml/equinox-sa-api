<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation;

$app = new Application();


$app->before(function (Request $request) {
    if (!in_array($request->server->get("REMOTE_ADDR"), array('127.0.0.1'))) {
        echo "Wrong IP";
        $app->abort();
    }
});

return $app;

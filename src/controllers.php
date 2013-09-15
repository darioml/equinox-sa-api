<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    return "{'message':'Welcome to equinox API'}";
})->bind('homepage');


$app->mount('/customer', new SAEApi\Controller\Customer());
$app->mount('/box',      new SAEApi\Controller\Box());


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) { return; }

    $reply = array(
        "error" => $code,
        "message"=>$e->getMessage()
    );
    return new Response(json_encode($reply), $code);
});

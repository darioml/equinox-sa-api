<?php

namespace SAEApi\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Customer implements ControllerProviderInterface {

    public function connect(Application $app)
    {
        $c = $app['controllers_factory'];

        $c->get('/{customerid}', function (Application $app, $customerid) {
            $text = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid));

            if ($text === false) {
                $app->abort('404', "invalid customer id");
            } else {
                $text['codes'] = $app['db']->fetchAssoc('SELECT * FROM codes WHERE boxID = ?', array($text['boxID']));
                return json_encode($text);
            }
        })->value('customerid', '0');

        $c->match('/{customerid}/codes', function(Application $app, $customerid) {
            $text = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid));

            if ($text === false) {
                $app->abort('404', "invalid customer id");
            } else {
                $text = $app['db']->fetchAssoc('SELECT * FROM codes WHERE boxID = ?', array($text['boxID']));
                return json_encode($text);
            }
        });

        return $c;
    }

}
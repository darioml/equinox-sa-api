<?php

namespace SAEApi\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Box implements ControllerProviderInterface {

    public function connect(Application $app)
    {
        $c = $app['controllers_factory'];


        $c->get('/', function (Application $app) {
            $app->abort('404', "Customer ID missing!");
        });

        $c->get('/{boxID}', function ($customerid) {
            $text = array(
                '50'=>array(
                    "name" => "Test Customer",
                    "address" => "Imperial College Road, SW72AZ",
                    "boxID" => "s00001",
                    "codes" => array(
                        "14241348","98574839","39481750"),
                    "totalpay" => "1374",
                    "freedays" => "0"
                )
            );
            return json_encode($text);
        });

        $c->post('/{customerid}/codes', function(Application $app) {
            return json_encode(array("success" => "true", "code" => "15487451", "boxID" => "s00001", "duration" => "14", "cost" => "18000"));
        });

        return $c;
    }

}
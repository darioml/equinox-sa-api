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
            if (!($text = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid)))) {
                $app->abort('404', "invalid customer id");
            } else {
                $text['codes'] = $app['db']->fetchAll('SELECT * FROM codes WHERE boxID = ? ORDER BY generated ASC', array($text['boxID']));
                foreach ($text['codes'] as $key=>$value)
                {
                    $code = new \SAEApi\Model\Code($value['code']);
                    $text['codes'][$key]['codeinfo'] = $code->getData();
                }
                return json_encode($text);
            }
        })->value('customerid', '0');


        $c->get('/{customerid}/codes', function(Application $app, Request $request, $customerid) {
            $text = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid));

            if ($text === false) {
                $app->abort('404', "invalid customer id");
            } else {
                $text['codes'] = $app['db']->fetchAll('SELECT * FROM codes WHERE boxID = ? ORDER BY generated ASC', array($text['boxID']));
                return json_encode($text['codes']);
            }
        });

        $c->post('/{customerid}/codes', function(Application $app, Request $request, $customerid) {
            $codeCost = $request->get('paid');
            if (!$request->get('paid')) {
                $app->abort('400', "Missing parameter");
            } else if (!is_numeric($request->get('paid'))) {
                $app->abort('400', "Paid amount must a number");
            }

            if (!($text = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid)))) {
                $app->abort('404', "invalid customer id");
            } else {
                if ( ($paidCode = \SAEApi\Model\Box::getLength(substr($text['boxID'], 0, 1), $codeCost)) == 0) {
                    $app->abort('400', "Paid amount must be enough to cover at least 2 days");
                }

                $count = $app['db']->fetchArray('SELECT COUNT(*) FROM codes WHERE boxID = ?', array($text['boxID']));
                $count = $count[0];

                $update = array(
                    'boxID'     => $text['boxID'],
                    'generated' => time(),
                    'code'      => $app['equinox.algorithm']->generate(substr($text['boxID'], 1), $count+1, $paidCode),
                    'free'      => 0,
                    'geninfo'   => 'api-0'
                );
                if ($app['db']->insert('codes', $update)) {
                    $app['db']->executeUpdate('UPDATE customers SET paid = paid + ? WHERE boxID = ?', array($codeCost, $text['boxID']));
                    return json_encode($update);
                }
                else
                    return $app->abort('500', "Failed to insert into database");
            }
        });


        return $c;
    }

}
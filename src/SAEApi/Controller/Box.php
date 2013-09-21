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

        $c->get('/{boxid}', function (Application $app, $boxid) {
            if (!preg_match("/^(s|l)[0-9]{5}$/", $boxid))
                $app->abort('400', "Invalid box ID");
            
            $return = array();
            if ($owner = $app['db']->fetchAssoc('SELECT * FROM customers WHERE boxID = ?', array($boxid))) {
                $return['customer'] = $owner;
            }
            if ($codes = $app['db']->fetchAll('SELECT * FROM codes WHERE boxID = ? ORDER BY generated ASC', array($boxid))) {
                $return['codes'] = $codes;
            }
            return json_encode($return);
        })->value('boxid', '0');

        $c->get('/{boxid}/codes', function (Application $app, $boxid) {
            if (!preg_match("/^(s|l)[0-9]{5}$/", $boxid))
                $app->abort('400', "Invalid box ID");
            $return = array();
            if ($codes = $app['db']->fetchAll('SELECT * FROM codes WHERE boxID = ? ORDER BY generated ASC', array($boxid))) {
                $return['codes'] = $codes;
            }
            return json_encode($return);
        })->value('boxid', '0');

        $c->post('/{boxid}/codes', function (Application $app, Request $request, $boxid) {
            $codeCost = $request->get('paid');
            if (!preg_match("/^(s|l)[0-9]{5}$/", $boxid)) {
                $app->abort('400', "Invalid box ID");
            } else if (!$request->get('paid')) {
                $app->abort('400', "Missing parameter");
            } else if (!is_numeric($request->get('paid'))) {
                $app->abort('400', "Paid amount must a number");
            } else if ( ($paidCode = \SAEApi\Model\Box::getLength(substr($boxid, 0, 1), $codeCost)) == 0) {
                $app->abort('400', "Paid amount must be enough to cover at least 2 days");
            } else if (!($customer = $app['db']->fetchAssoc('SELECT * FROM customers WHERE boxID = ?', array($boxid)))) {
                $app->abort('400', "Box not linked to any customer");
            }

            $count = $app['db']->fetchArray('SELECT COUNT(*) FROM codes WHERE boxID = ?', array($boxid));
            $count = $count[0];

            $update = array(
                'boxID'     => $boxid,
                'generated' => time(),
                'code'      => $app['equinox.algorithm']->generate(substr($boxid, 1), $count, $paidCode),
                'free'      => 0,
                'geninfo'   => 'api-1'
            );
            if ($app['db']->insert('codes', $update)) {
                $app['db']->executeUpdate('UPDATE customers SET paid = paid + ? WHERE boxID = ?', array($codeCost, $boxid));
                return json_encode($update);
            }
            else
                return $app->abort('500', "Failed to insert into database");
        })->value('boxid', '0');

        $c->get('/{boxid}/initiate', function (Application $app, Request $request, $boxid) {
            $count = $app['db']->fetchArray('SELECT COUNT(*) FROM codes WHERE boxID = ?', array($boxid));

            if (!preg_match("/^(s|l)[0-9]{5}$/", $boxid)) {
                $app->abort('400', "Invalid box ID");
            } else if (($customer = $app['db']->fetchAssoc('SELECT * FROM customers WHERE boxID = ?', array($boxid)))) {
                $app->abort('400', "Box must not be linked to any customer");
            } else if ($count[0] > 0) {
                $app->abort('400', "Box must not have any codes");
            }

            $update = array(
                'boxID'     => $boxid,
                'generated' => time(),
                'code'      => $app['equinox.algorithm']->generate(substr($boxid, 1), 0, 1),
                'free'      => 1,
                'geninfo'   => 'api-2'
            );
            if ($app['db']->insert('codes', $update)) {
                return json_encode($update);
            }
            else
                return $app->abort('500', "Failed to insert into database");
        })->value('boxid', '0');

        return $c;
    }

}
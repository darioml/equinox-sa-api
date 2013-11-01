<?php
/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

*/

namespace SAEApi\Model;
use Silex\Application;

class Customer
{
    protected $app,
        $valid,
        $customer,
        $codes;

    function __construct(Application $app, $customerid)
    {
        $this->app = $app;
        $this->customer = $app['db']->fetchAssoc('SELECT * FROM customers WHERE customerID = ?', array($customerid));
    }

    function getInfo()
    {
        return $this->customer;
    }

    function getId()
    {
        return $this->customer['customerID'];
    }

    function isValid()
    {
        if ($this->customer == false)
            return false;
        return true;
    }

    function getCodes($limit = 5) {
        if (!$this->isValid)
            return false;

        //lazy loaddd!
        if ($codes == null) {
            $this->codes = $this->app['db']->fetchAll('SELECT * FROM codes WHERE boxID = ? ORDER BY generated DESC LIMIT ' + $limit, array($this->customer['boxID']));
        }

        return $this->codes;
    }

    function getBoxClass()
    {
        if (!$this->isValid)
            return false;

        if ($this->box == null) {
            $this->box = new Box($this->customer['boxID']);
        }

        return $this->box;
    }

    function addPayment($amount)
    {

    }

    function addCode()
    {

    }
}


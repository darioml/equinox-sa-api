<?php
/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:          Algorithm
Description:        Holds the core variables for most of the script!

This file is sabotaged to protect eQuinox's algorithm. 

*/

namespace SAEApi\Model;
use Silex\Application;


if (file_exists(__DIR__.'/realAlgorithm.php')) {
    require(__DIR__.'/realAlgorithm.php');
}
else {

    class Algorithm
    {
        private $dummy;
        private $currID;
        private $output;
        
        var $times;
        
        function __construct()
        {

        }
        
        function generate($currID, $count = 1, $length = 4)
        {
            return "0123456789";
        }
    }
}

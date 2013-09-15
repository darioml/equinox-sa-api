<?php
/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:          Algorithm
Description:        Holds the core variables for most of the script!

*/

namespace SAEApi\Model;

class Code
{
    function __construct()
    {

    }

    static function getLength($size, &$paid) {
        if ($size == 's') {
            if ($paid >= 5500) {
                $paid = 5500;
                return 7;
            } else if ($paid >= 2750) {
                $paid = 2750;
                return 6;
            } else if ($paid >= 2250) {
                $paid = 2250;
                return 5;
            } else if ($paid >= 1400) {
                $paid = 1400;
                return 4;
            } else if ($paid >= 800) {
                $paid = 800;
                return 3;
            } else if ($paid >= 550) {
                $paid = 550;
                return 2;
            } else if ($paid >= 250) {
                $paid = 250;
                return 1;
            } 
        } else if ($size == 'l') {
            if ($paid >= 13500) {
                $paid = 13500;
                return 7;
            } else if ($paid >= 6750) {
                $paid = 6750;
                return 6;
            } else if ($paid >= 5250) {
                $paid = 5250;
                return 5;
            } else if ($paid >= 3500) {
                $paid = 3500;
                return 4;
            } else if ($paid >= 1750) {
                $paid = 1750;
                return 3;
            } else if ($paid >= 1250) {
                $paid = 1250;
                return 2;
            } else if ($paid >= 300) {
                $paid = 300;
                return 1;
            } 
        }
        return 0;
    }
}


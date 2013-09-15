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

class Box
{
    function __construct()
    {

    }

    static function getLength($size, $paid) {
        if ($size == 's') {
            if ($paid >= 5500) {
                return 7;
            } else if ($paid >= 2750) {
                return 6;
            } else if ($paid >= 2250) {
                return 5;
            } else if ($paid >= 1400) {
                return 4;
            } else if ($paid >= 800) {
                return 3;
            } else if ($paid >= 550) {
                return 2;
            } else if ($paid >= 250) {
                return 1;
            } 
        } else if ($size == 'l') {
            if ($paid >= 13500) {
                return 7;
            } else if ($paid >= 6750) {
                return 6;
            } else if ($paid >= 5250) {
                return 5;
            } else if ($paid >= 3500) {
                return 4;
            } else if ($paid >= 1750) {
                return 3;
            } else if ($paid >= 1250) {
                return 2;
            } else if ($paid >= 300) {
                return 1;
            } 
        }
        return 0;
    }
}


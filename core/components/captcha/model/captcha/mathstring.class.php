<?php
/**
 * Captcha
 *
 * Copyright 2011-2019 Bob Ray
 * @author Bob Ray <https://bobsguides.com>
 * 1/17/11
 *
 * Captcha is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Captcha is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Captcha; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package captcha
 */
/**
 * Description Creates math equation for Captcha package
 * @package captcha
 */

/** MathString Class
 * @package captcha
 */
/*  File: mathstringclass.inc.php

 Version: 1.0.2
 $Revision: 82 $
 $Author: Bob Ray $
 $Date: 2008-01-29 21:26:28 -0500 (Tue, 29 Jan 2008) $

 Used to verify that a human is filling out the contact form having the user
 solve a simple math equation.

 This class creates a quasi-random string that contains a math problem with
 a single digit answer (e.g. "2 * 2" or "3 - 1"). It returns the string as
 a string through the function getDisplayString(). The string can then be
 displayed as a plain string, or passed to an appropiate Captcha-style
 image rendering function.

 The class returns the one- or two-digit solution to the math equation as an
 integer with the function getValue(). This value can then be compared
 with the user's input.

 If you want tougher math problems, you can pass arguments to the constructor
 (e.g. $ms = new mathstring("456","789")) but it may annoy your users.

 Example:
    <?php
    include ("mathstringclass.inc.php");
    $ms = new mathstring;
    $displayString = $ms->getDisplayString();
    $answer = $ms->getValue();
    // form displaying string and getting user's answer here
    // where form is validated, put if ($userAnswer != $answer) { echo "you're no human";}
    ?>
*/

    class MathString {
        /**
        * @var string String to display
        */
        var $_displayString="";
        /**
        * @var int equation solution
        */
        var $_value;

        /**
         * @param string $s1 String containing digits for left side of equation
         * @param string $s2 String containing digits for right side of equation
         * @return void
         */
        function MathString($s1 = "34", $s2 = "312") { // Constructor creates the math expression as a string
            $i1 = rand(0, 1);
            $i2 = rand(0, 2);
            $operators = "+-x";
            $this->_displayString = substr($s1, $i1, 1) . ' ' . substr($operators, rand(0, 2), 1) . ' ' . substr($s2, $i2, 1);
        }

        /** Get the equation as a string
         * @return string - Equation as string
         */
        function getDisplayString() { // returns the math expression as a string
            return ($this->_displayString);

        }

        /** Get the equation's solution
         * @return int solution to equation
         */
        function getValue() { // returns the solution as an integer
            $value = null;
            $ds = $this->_displayString;
            $v1 = intval(substr($ds, 0, 1));
            $v2 = intval(substr($ds, 4, 1));
            $op = substr($ds, 2, 1);
            switch ($op) {
                case "+":
                    $value =  ($v1 + $v2);

                    break;
                case "-":
                    $value =  ($v1 - $v2);

                    break;
                case "x":
                    $value =  ($v1 * $v2);

                    break;
            }
            return $value;

        }

  }


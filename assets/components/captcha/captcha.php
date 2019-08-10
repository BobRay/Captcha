<?php
/**
 * Captcha
 *
 * Copyright 2011-2019 Bob Ray
 *
 * @author Bob Ray
 * @editor Shaun McCormick <shaun@collabpad.com>
 * @created 09-23-2008

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
 * MODX Captcha code - produces Captcha image
 *
 * Description Presents Captcha-style image for login verification
  *
 * @package captcha
 *
 * Properties passed in $_SESSION variables
 * @property $captcha.use_mathstring boolean - create math equation
 * @property $captcha.height string - string height of captcha image
 * @property $captcha.width string - width of captcha image
 *

 */

$captcha_assets_path = dirname(__FILE__).'/';
include dirname(dirname(dirname($captcha_assets_path))).'/config.core.php';
$captcha_core_path = MODX_CORE_PATH.'components/captcha/';

/* include the modX class */
require_once MODX_CORE_PATH . "model/modx/modx.class.php";

$modx= new modX();
$modx->initialize('web');
$modx->addPackage('captcha',$captcha_core_path.'model/');
$modx->loadClass('captcha.VeriWord',$captcha_core_path.'model/',true,true);
$modx->lexicon->load('captcha:default');

/* let the caller override the default height and width with session variables */

$height = $modx->getOption('captcha.height',$_SESSION,$modx->getOption('captcha.height',null,80));
$width = $modx->getOption('captcha.width',$_SESSION,$modx->getOption('captcha.width',null,200));

/* If the caller has set the captcha_use_mathstring variable, use it;
 * otherwise, use the System Setting
 */

$useMathstring = false;


if (isset($_SESSION['captcha.use_mathstring'])) {
    if ($_SESSION['captcha.use_mathstring'] == 'true')  {
        $useMathString = true;
    } else {
        $useMathString = false;
    }
} else {
    $useMathString = $modx->getOption('captcha.use_mathstring',null,true);
}

if ($useMathString) {
    $vword = new VeriWord($modx,$width,$height, 'MathString');
} else {
    $vword = new VeriWord($modx,$width,$height);
}
$vword->output_image();
$vword->destroy_image();
session_write_close();
exit();
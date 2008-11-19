<?php
/**
 * @package captcha
 * @author Bob Ray <bobray@softville.com>
 * @editor Shaun McCormick <shaun@collabpad.com>
 * @created 09-23-2008
 * @version 1.0
 */
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR);

// include the modX class
if (!require_once (MODX_CORE_PATH . "model/modx/modx.class.php")) {
  die("Can't find MODx class file");
}

$modx= new modX();
$modx->initialize('web');

require_once dirname(__FILE__).'/classes/veriword.class.php';

$modx->lexicon->load('captcha:default');

/* let the caller override the default height and width with session variables */

$height = isset($_SESSION['captcha_height'])? $_SESSION['captcha_height'] : 80;
$width = isset($_SESSION['captcha_width'])? $_SESSION['captcha_width'] : 200;

/* If the caller has set the captcha_use_mathstring variable, use it;
 * otherwise, use the System Setting
 */

$useMathstring = false;


if (isset($_SESSION['captcha_use_mathstring'])) {
    if ($_SESSION['captcha_use_mathstring'] == 'true' || $_SESSION['captcha_use_mathstring'] == true)  {
        $useMathString = true;
    } else {
        $useMathString = false;
    }
} else {
    $useMathString = $modx->config['captcha_use_mathstring'];
}

if ($useMathString) {
    $vword = new VeriWord($modx,$width,$height, "MathString");
} else {
    $vword = new VeriWord($modx,$width,$height);
}
$vword->output_image();
$vword->destroy_image();
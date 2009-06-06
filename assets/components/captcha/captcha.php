<?php
/**
 * @package captcha
 * @author Bob Ray <bobray@softville.com>
 * @editor Shaun McCormick <shaun@collabpad.com>
 * @created 09-23-2008
 * @version 1.0
 */
$captcha_assets_path = dirname(__FILE__).'/';
include dirname(dirname(dirname($captcha_assets_path))).'/config.core.php';
$captcha_core_path = MODX_CORE_PATH.'components/captcha/';

/* include the modX class */
if (!require_once (MODX_CORE_PATH . "model/modx/modx.class.php")) {
  die("Can't find MODx class file");
}

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
    if ($_SESSION['captcha.use_mathstring'] == 'true' || $_SESSION['captcha.use_mathstring'] == true)  {
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
exit();
<?php
/**
 * @package captcha
 * @author Bob Ray <bobray@softville.com>
 * @editor Shaun McCormick <shaun@collabpad.com>
 * @created 09-23-2008
 * @version 1.0
 */
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR);

// include the modX class
if (!require_once (MODX_CORE_PATH . "model/modx/modx.class.php")) {
  die("Can't find MODx class file");
}

$modx= new modX();
$modx->initialize('web');

require_once dirname(__FILE__).'/classes/veriword.class.php';

$modx->lexicon->load('captcha:default');

$useMathstring = $modx->getObject('modSystemSetting','captcha_use_mathstring');

if ($useMathstring !== null && ($useMathstring->value == 1 || $useMathstring->value == '1' || $useMathstring->value == 'Yes')) {
    $vword = new VeriWord($modx,200,80, "MathString");
} else {
    $vword = new VeriWord($modx,200,80);
}
$vword->output_image();
$vword->destroy_image();
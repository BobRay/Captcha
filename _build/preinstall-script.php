<?php
/**
 * @package captcha
 * Validator -- checks for GD and FreeType
 */
/* $oldLogLevel = $object->xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$oldLogTarget = $object->xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');  */

$object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Running PHP Validator.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:

        $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Checking for GD and FreeType: ');
        $success = true;
        /* Check for GD library */
        if (function_exists('imagegd2')) {
            $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'GD lib found');
        } else {
            $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'GD lib not found -- install canceled');
            return false;
        }
        /* GD is ok, check for necessary functions */

        $fs = array('imagettfbbox', 'imagecreate', 'imagecolorallocate', 'imagettftext',
        'imagecolortransparent', 'imagedestroy', 'imagecreatefromjpeg', 'imagesx',
        'imagesy', 'imagecreatetruecolor', 'imagecopyresampled', 'imagecopymerge'
        );
        $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Checking for necessary functions');


        foreach($fs as $f) {
            $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Checking for ' . $f . ' function: ');
            if (function_exists($f)) {
                $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'OK');
            } else {
                $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Not found');
                $success =  false;
            }
        }
        if ($success == false) {
            $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Configuration problem - install canceled');
            return false;
        } else {
            $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'No Problems -- installing CAPTCHA plugin');
        }

        break;
   case xPDOTransport::ACTION_UPGRADE:
        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

/*$object->xpdo->setLogLevel($oldLogLevel);
$object->xpdo->setLogTarget($oldLogTarget); */

return $success;
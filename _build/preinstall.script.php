<?php
/**
 * Captcha pre-install script
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
 * Description: Checks for Necessary GD and FreeType functions
 * @package captcha
 * @subpackage build
 */
/**
 * @package captcha
 * Validator -- checks for GD and FreeType - only on first install
 */

/* @var $object modTransportPackage */
/* @var $options array */
$object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Running PHP Validator.');
$success = false;
switch($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:

        $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Checking for GD and FreeType: ');

        /* Check for GD library */
        if (function_exists('imagegd2')) {
            $object->xpdo->log(xPDO::LOG_LEVEL_INFO,'GD lib found');
            $success = true;
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
        /* Remove Captcha System Settings */

        $settings = array(
            'captcha.enabled',
            'captcha.words',
            'captcha.use_mathstring',
            'captcha.height',
            'captcha.width'
        );
        foreach ($settings as $setting) {
            $ss = $object->xpdo->getObject('modSystemSetting', array('key' => $setting));
            if ($ss) {
                $ss->remove();
            }
        }
        $success = true;
        break;
}



return $success;
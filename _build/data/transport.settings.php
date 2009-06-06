<?php
/**
 * @package captcha
 * @subpackage build
 */
$settings = array();

$settings['captcha.words']= $modx->newObject('modSystemSetting');
$settings['captcha.words']->fromArray(array (
    'key' => 'captcha.words',
    'value' => 'Ack,Arps,Alag,Atex,Bek,Bux,Chux,Caxt,Depp,Dex,Ext,Enya,Fet,Fets,Tek,Text,Gurk,Gex,Het,Heft,Unet,Ibex,Jax,Jerp,Jenk,Lak,Lest,Lev,Mars,Mamp,Nex,Nelp,Paxt,Pex,Reks,Rux,Snix,Sept,Turp,Thix,Elps,Vux,Veks,Wect,Wex,Yap,Yef,Yeff,Zub,Zeks',
    'xtype' => 'textarea',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['captcha.enabled']= $modx->newObject('modSystemSetting');
$settings['captcha.enabled']->fromArray(array (
    'key' => 'captcha.enabled',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['captcha.use_mathstring']= $modx->newObject('modSystemSetting');
$settings['captcha.use_mathstring']->fromArray(array (
    'key' => 'captcha.use_mathstring',
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['captcha.height']= $modx->newObject('modSystemSetting');
$settings['captcha.height']->fromArray(array (
    'key' => 'captcha.height',
    'value' => '80',
    'xtype' => 'textfield',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['captcha.width']= $modx->newObject('modSystemSetting');
$settings['captcha.width']->fromArray(array (
    'key' => 'captcha.width',
    'value' => '200',
    'xtype' => 'textfield',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

return $settings;
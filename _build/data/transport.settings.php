<?php
$settings['captcha_words']= $modx->newObject('modSystemSetting');
$settings['captcha_words']->fromArray(array (
    'key' => 'captcha_words',
    'value' => 'Ack,Arps,Alag,Atex,Bek,Bux,Chux,Caxt,Depp,Dex,Ext,Enya,Fet,Fets,Tek,Text,Gurk,Gex,Het,Heft,Unet,Ibex,Jax,Jerp,Jenk,Lak,Lest,Lev,Mars,Mamp,Nex,Nelp,Paxt,Pex,Reks,Rux,Snix,Sept,Turp,Thix,Elps,Vux,Veks,Wect,Wex,Yap,Yef,Yeff,Zub,Zeks',
    'xtype' => 'textarea',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['use_captcha']= $modx->newObject('modSystemSetting');
$settings['use_captcha']->fromArray(array (
    'key' => 'use_captcha',
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);

$settings['captcha_use_mathstring']= $modx->newObject('modSystemSetting');
$settings['captcha_use_mathstring']->fromArray(array (
    'key' => 'captcha_use_mathstring',
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'captcha',
    'area' => 'captcha',
), '', true, true);


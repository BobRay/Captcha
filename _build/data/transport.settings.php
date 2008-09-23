<?php
$settings['captcha_words']= $modx->newObject('modSystemSetting');
$settings['captcha_words']->fromArray(array (
    'key' => 'captcha_words',
    'value' => 'MODx,Access,Better,BitCode,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Tattoo,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote',
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


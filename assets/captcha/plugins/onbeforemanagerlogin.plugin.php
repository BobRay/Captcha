<?php
$rt = true;
$modx->lexicon->load('captcha:default');
if (isset ($modx->config['use_captcha']) && $modx->config['use_captcha'] == 1) {

    if (!isset ($_SESSION['veriword'])) {

         $rt = '$_SESSION Variable not set';

    }  else if ($_SESSION['veriword'] != $_POST['captcha_code']) {

         //$rt = "Debug: No Match: SESSION:".$_SESSION['veriword']." captcha_code:".$_POST['captcha_code'];
         if (isset ($modx->config['captcha_use_mathstring']) && $modx->config['captcha_use_mathstring'] == 1) {
             $rt=$modx->lexicon('login_mathstring_error');
         } else {
             $rt=$modx->lexicon('login_captcha_error');
         }

    }

}

$modx->event->_output = $rt;
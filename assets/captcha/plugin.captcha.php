<?php
/**
 * Captcha Plugin
 *
 * Events: OnBeforeManagerLogin, OnManagerLoginFormRender
 *
 * @author Bob Ray <bobray99@gmail.com>
 * @editor Shaun McCormick <splittingred@gmail.com>
 * @created 09/23/2008
 * @version 1.0
 */
switch ($modx->event->name) {
    case 'OnBeforeManagerLogin': // register only for backend
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
        break;
    case 'OnManagerLoginFormRender': // register only for backend
        $rt = '';

        if (isset ($modx->config['use_captcha']) && $modx->config['use_captcha'] == 1) {

           $modx->lexicon->load('captcha:default');

           if (isset ($modx->config['captcha_use_mathstring']) && $modx->config['use_mathstring'] == 1) {
               $alt = $modx->lexicon("login_mathstring_message");
           } else {
               $alt = $modx->lexicon("login_captcha_message");
           }

           $captcha_image= '<a href="'.$_SERVER['PHP_SELF'].'" class="loginCaptcha"><img src="'.$modx->config['site_url'].'assets/captcha/captcha.php?rand='.rand().'" alt="'.$alt.'" /></a>';

        if (isset ($modx->config['captcha_use_mathstring']) && $modx->config['captcha_use_mathstring'] == 1) {
               $captcha_prompt = '<p>'.$modx->lexicon("login_mathstring_message").'</p>';
               $captcha_input= '<br /><br /><label>'.$modx->lexicon("captcha_mathstring_code").': <input type="text" name="captcha_code" tabindex="3" value="" /></label>';
           } else {
               $captcha_prompt = '<p>'.$modx->lexicon("login_captcha_message").'</p>';
               $captcha_input= '<br /><br /><label>'.$modx->lexicon("captcha_code").': <input type="text" name="captcha_code" tabindex="3" value="" /></label>';
           }

           $rt = '<hr />'.$captcha_prompt.$captcha_image.$captcha_input;
        }

        $modx->event->_output = $rt;
        break;
}
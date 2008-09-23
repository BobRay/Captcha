<?php
$rt = "";

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
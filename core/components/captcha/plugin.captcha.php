<?php
/**
 * Captcha Plugin
 *
 * Events: OnBeforeManagerLogin, OnManagerLoginFormRender
 *
 * @package captcha
 * @author Bob Ray <bobray@softville.com>
 * @editor Shaun McCormick <shaun@collabpad.com>
 * @created 09/23/2008
 * @version 1.0
 */
switch ($modx->event->name) {
    case 'OnBeforeManagerLogin': /* register only for backend */
        $rt = true;
        $modx->lexicon->load('captcha:default');
        if ($modx->getOption('captcha.enabled',null,true)) {
            if (!isset ($_SESSION['veriword'])) {
                 $rt = '$_SESSION Variable not set';

            }  else if ($_SESSION['veriword'] != $_POST['captcha_code']) {
                /*$rt = "Debug: No Match: SESSION:".$_SESSION['veriword']." captcha_code:".$_POST['captcha_code']; */
                if ($modx->getOption('captcha.use_mathstring',null,true)) {
                    $rt=$modx->lexicon('login_mathstring_error');
                } else {
                    $rt=$modx->lexicon('login_captcha_error');
                }
            }
        }

        $modx->event->_output = $rt;
        break;

    case 'OnManagerLoginFormRender': /* register only for backend */
        $rt = '';

        if ($modx->getOption('captcha.enabled',null,true)) {

            $modx->lexicon->load('captcha:default');

            if ($modx->getOption('captcha.use_mathstring',null,true)) {
                $alt = $modx->lexicon('login_mathstring_message');
            } else {
                $alt = $modx->lexicon('login_captcha_message');
            }

            $captcha_image= '<a href="'.$_SERVER['PHP_SELF'].'" class="loginCaptcha"><img src="'.$modx->getOption('site_url').'assets/components/captcha/captcha.php?rand='.rand().'" alt="'.$alt.'" /></a>';

            if ($modx->getOption('captcha_use_mathstring',null,true)) {
                $captcha_prompt = '<p>'.$modx->lexicon("login_mathstring_message").'</p>';
                $captcha_input= '<br /><br /><label>'.$modx->lexicon("captcha_mathstring_code").': <input type="text" name="captcha_code" value="" /></label>';
            } else {
                $captcha_prompt = '<p>'.$modx->lexicon("login_captcha_message").'</p>';
                $captcha_input= '<br /><br /><label>'.$modx->lexicon("captcha_code").': <input type="text" name="captcha_code" value="" /></label>';
            }

            $rt = '<hr />'.$captcha_prompt.$captcha_image.$captcha_input;
        }

        $modx->event->_output = $rt;
        break;
}
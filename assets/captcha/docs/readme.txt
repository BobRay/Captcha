Modx Captcha Utility
====================
Author: BobRay <bobray@softville.com>
Date:   08/10/2008
====================

This utility provides captcha verification in the MODx
Manager login.

It uses to three system settings:

use_captcha                If set, the captcha function is turned on
captcha_use_mathstring     If set, the image is a simple equation for the user to solve 
captcha_words              Selection of words used for the image if captcha_use_mathstring is off

You may add or delete fonts from the assets/captcha/fonts directory 
as long as there is at least one .ttf file there.

You may add or delete images from the assets/captcha/noise directory 
as long as there is at least one file there.

Captcha can also be used in other forms that require captcha.

There are various ways to use it in other forms.

You can roll your own function by calling captcha.php where you want the image 
and then comparing the user's input to the $_SESSION['veriword'] variable.

You can also use the code in plugins.

To do so, look at the code in the two plugins 
(in the assets/captcha/plugins directory):

OnManagerLoginFormPrerender shows the code which displays the image
OnBeforeManagerLogin shows the code which validates the user's input

You can paste the code from these two plugins into two plugins of your own.

On your login form, where you want the image to appear, use a snippet with this code:

$eventInfo= $modx->invokeEvent('YourImagePlugin');
$eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
$output .= $eventInfo;
return $output;

In the document the user is sent to after the login, put a snippet with this code:

$rt = $modx->invokeEvent('YourCaptchaVerificationPlugin');

/* If the event fired, loop through the event array and fail if there's an error message  */
if (is_array($rt)) {
    foreach ($rt as $key=>$value) {   //php4 compatible
        if ($value !== true) {
            $modx->error->failure($value);  // or use sendRedirect to send the user to an error page
        }
    }
    unset($key,$value);
    }



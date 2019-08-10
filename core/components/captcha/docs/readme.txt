Modx Captcha Utility
====================
Author: BobRay <bobray@softville.com>
Copyright 2008-2019 Bob Ray
Date:   08/10/2008
Updated:10/03/2010
====================

This utility provides captcha verification in the MODX
Manager login and elsewhere.

It uses the following System Settings:

captcha.enabled            If set, captcha is used for Manager login (off when installed)
captcha.use_mathstring     If set, the image is a simple equation for the user to solve (on when installed)
captcha.words              Selection of words used for the image if captcha_use_mathstring is off
captcha.height             Height of image (default: 80)
captcha.width              Width of image (default: 200)
captcha.words              Words to use in CAPTCHA challenge

You may add or delete fonts from the core/components/captcha/fonts directory
as long as there is at least one .ttf file there.

You may add or delete images from the core/components/captcha/noise directory
as long as there is at least one file there.

Captcha can also be used in other forms that require captcha.

You can roll your own function by calling assets/components/captcha/captcha.php where you want the image
and then comparing the user's input to the $_SESSION['veriword'] variable.

See the code in the core/components/captcha/captcha.plugin.php file.

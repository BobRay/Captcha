<?php
/**
 * @package modx
 * @subpackage assets.captcha
 */

######
## Verification Word
######
## This class generate an image with random text
## to be used in form verification. It has visual
## elements design to confuse OCR software preventing
## the use of BOTS.
##
#######
## Author: Huda M Elmatsani
## Email: 	justhuda at netrada.co.id
##
## 25/07/2004
#######
## Copyright (c) 2004 Huda M Elmatsani All rights reserved.
## This program is free for any purpose use.
########
##
## USAGE
## create some image with noise texture, put in image directory,
## rename to noise_#, see examples
## put some true type font into font directory,
## rename to font_#, see exmplae
## you can search and put free font you like
##
##
## modified for use within MODx CMF, 17/02/08
##
## modified for MODx Revolution, Bob Ray 08/10/08
####
class VeriWord {

	/* path to font directory*/
	var $dir_font;
	/* path to background image directory*/
	var $dir_noise;
    var $word 		= "";
	var $im_width 	= 0;
	var $im_height 	= 0;
	var $modx;

	function VeriWord(&$modx, $w=200, $h=80, $word = "") {
		$this->modx = $modx;

        $this->base_path = dirname(dirname(__FILE__)).'/';
        $this->dir_font = $this->base_path.'fonts';
		$this->dir_noise = $this->base_path.'noise';
        $this->dir_mathstring = $this->base_path.'classes';
		$this->set_veriword($word);
		$this->im_width 		= $w;
		$this->im_height 		= $h;
	}

	function set_veriword($word = "") {
		/* create session variable for verification,
		   you may change the session variable name */

         if ($word == "MathString") {
            require $this->dir_mathstring."/mathstring.class.php";
            $ms = new MathString();
            $this->word = $ms->getDisplayString();
            $_SESSION['veriword']     = $ms->getValue();

         } elseif ($word != "") {

            $this->word = $word;
            $_SESSION['veriword'] = $this->word;

        } else {

		   $this->word 	= $this->pick_word();
           $_SESSION['veriword'] = $this->word;
        }

	}

	function output_image() {
		/* output the image as jpeg */
		$this->draw_image();
		header("Content-type: image/jpeg");
		imagejpeg($this->im);
	}

	function pick_word() {
		// set default words
/* remove this Ack,Arps,Alag,Atex,Bek,Bux,Chux,Caxt,Depp,Dex,Ext,Enya,Fet,Fets,Tek,Toxt,Gurk,Gex,Het,Heft,Unet,Ibex,Jax,Jerp,Jenk,Lak,Lest,Lev,Mars,Mamp,Nex,Nelp,Paxt,Pex,Reks,Rux,Snux,Sept,Turp,Thex,Erps,Vux,Veks,Wect,Wex,Yap,Yef,Yeff,Zub,Zeks  */


		$words="MODx,Access,Better,BitCode,Chunk,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Oscope,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote";
		// get words from database
		$wordSettings = $this->modx->getObject('modSystemSetting',array('key' => 'captcha_words'));

		if (strlen($wordSettings->value) > 0) $words = $wordSettings->value;
		$arr_words = explode(",", $words);
		/* pick one randomly for text verification  */
        $wd = $arr_words[array_rand($arr_words)];
        $num = rand(101,999);
        /* get rid of the ones and zeros */

        return $wd.str_replace(array('0','1'),array('3','7'),$num);

	}

	function draw_text() {

		// dynamically load GD2 lib
		if (!extension_loaded('gd')) {
		   if (strtoupper(substr(PHP_OS, 0,3) == 'WIN')) {
				@dl('php_gd2.dll');
		   }
		   else {
				@dl('gd2.so');
		   }
		}

		/* pick one font type randomly from font directory */

			// added by Alex - read ttf dir
			$dir = dir($this->dir_font);
			$fontstmp = array();
			while ($file = $dir->read()) {

                if(strstr($file,'.ttf') ) {  // include only .ttf files
					$fontstmp[] = $this->dir_font.'/'.$file;
				}
			}
			$dir->close();
			$text_font = sprintf("%s",$fontstmp[array_rand($fontstmp)]);
		/* angle for text inclination */
		$text_angle = rand(-9,9);
		/* initial text size */
		$text_size	= 30;
		/* calculate text width and height */
		$box 		= imagettfbbox ( $text_size, $text_angle, $text_font, $this->word);
		$text_width	= $box[2]-$box[0]; //text width
		$text_height= $box[5]-$box[3]; //text height

		/* adjust text size */
		$text_size  = round((20 * $this->im_width)/$text_width);

		/* recalculate text width and height */
		$box 		= imagettfbbox ( $text_size, $text_angle, $text_font, $this->word);
		$text_width	= $box[2]-$box[0]; //text width
		$text_height= $box[5]-$box[3]; //text height

		/* calculate center position of text */
		$text_x     	= ($this->im_width - $text_width)/2;
		$text_y 		= ($this->im_height - $text_height)/2;

		/* create canvas for text drawing */
		$im_text 		= imagecreate ($this->im_width, $this->im_height);
   		$bg_color 		= imagecolorallocate ($im_text, 255, 255, 255);

		/* pick color for text */
		$text_color 	= imagecolorallocate ($im_text, 0, 51, 153);

		/* draw text into canvas */
		imagettftext	(	$im_text,
							$text_size,
							$text_angle,
							$text_x,
							$text_y,
							$text_color,
							$text_font,
							$this->word);

		/* remove background color */
		imagecolortransparent($im_text, $bg_color);
		return $im_text;
		imagedestroy($im_text);
	}


	function draw_image() {

		/* pick one background image randomly from image directory */
		$img_file 		= $this->dir_noise."/noise".rand(1,4).".jpg";
		/* create "noise" background image from your image stock*/
		$noise_img 		= @imagecreatefromjpeg ($img_file);
 		$noise_width 	= imagesx($noise_img);
		$noise_height 	= imagesy($noise_img);

		/* resize the background image to fit the size of image output */
		$this->im 		= imagecreatetruecolor($this->im_width,$this->im_height);
		imagecopyresampled ($this->im,
							$noise_img,
							0, 0, 0, 0,
							$this->im_width,
							$this->im_height,
							$noise_width,
							$noise_height);

		/* put text image into background image */
		imagecopymerge ( 	$this->im,
							$this->draw_text(),
							0, 0, 0, 0,
							$this->im_width,
							$this->im_height,
							70 );

		return $this->im;
	}

	function destroy_image() {

		imagedestroy($this->im);

	}

}

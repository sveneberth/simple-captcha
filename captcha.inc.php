<?php
/**
 * A simple PHP Captcha Script
 * [link]
 *
 * Copyright 2015 Sven Eberth
 * Released under the MIT license, see LICENSE.txt
 */

class simple_captcha {

	/* ### DEFAULT SETTINGS ### */
	public $characters = "ABCDEFGHJKMNQRTUVWXYabdefghjmnqrtvy23456789";
	public $length = 8;
	
	public $background_image = 'images/background.png';
	
	public $font = 'font/fradm.ttf';
	public $fontColor = array(255, 255, 255);
	public $fontsize = 20;

	public $positionX = 25;
	public $positionY = 50;
	public $angleRange = 20;
	public $positionRange = 5;
	
	
	private function is_session_started() {
		if(php_sapi_name() !== 'cli') {
			if(version_compare(phpversion(), '5.4.0', '>=')) {
				return session_status() === PHP_SESSION_ACTIVE ? true : false;
			} else {
				return session_id() === '' ? false : true;
			}
		}
		return false;
	}
	
	protected function createCode() {
		$code = '';
		while(strlen($code) < $this->length) { // get random char
			$code .= substr($this->characters, rand() % (strlen($this->characters)), 1);
		}
		
		if(!$this->is_session_started()) // start session, if not already started
			session_start();
		
		$_SESSION['_CAPTCHA']['code'] = $code; // write in session
		
		return $code;
	}
	
	public function createCaptcha() {
		$code = $this->createCode();

		list($red, $green, $blue) = $this->fontColor;
		
		$image = ImageCreateFromPNG($this->background_image); // create image from background
		
		$color = ImageColorAllocate ($image, $red, $green, $blue);
	
	
		for($i = 1; $i <= $this->length; $i++) {
			ImageTTFText // create letter
			(
				$image, // background-image
				$this->fontsize, // font-size
				rand(- $this->angleRange, $this->angleRange), // angle
				$this->positionX * $i + rand(- $this->positionRange, $this->positionRange), // Position-X
				$this->positionY + rand(- $this->positionRange, $this->positionRange), // Position-Y
				$color, // font-color
				$this->font, // font
				$code[$i - 1] // value
			);
		}
		
		
		ob_start(); // start output buffering
		
			ImagePng($image);
			ImageDestroy($image);
		
		$imagedata = ob_get_clean(); // get and delete buffer content
		
		return 'data:image/png;base64,' . base64_encode($imagedata);
	}
	
	public function createHTMLImage() {
		return '<img src="' . $this->createCaptcha() . '" alt="Captcha Code" />';
	}
}
?>
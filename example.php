<?php
/**
 * A simple PHP Captcha Script
 * https://github.com/sveneberth/simple-captcha
 *
 * Copyright 2015 Sven Eberth
 * Released under the MIT license, see LICENSE.txt
 */

session_start();

require('simple-captcha.inc.php');

$captcha = new simple_captcha;
$captcha->positionX = 45;
$captcha->length = 5;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Simple Captcha - Example</title>
	</head>
	<body>
		
		<?php
			if(isset($_POST['submit']))
			{
				if($_POST['captcha'] == $_SESSION['_CAPTCHA']['code'])
				{
					echo "<p>The Captcha has been entered correctly!</p>";
				}
				else
				{
					echo "<p>The Captcha has been entered incorrectly! Please try it again.</p>";
				}
			}
		?>
		<form name="myform" action="" method="post">
			
			<p>Please enter the Captcha Code to confirm, that you are a human.</p>
			
			<?= $captcha->createHTMLImage() ?>
			<br />
			
			<input type="text" name="captcha" placeholder="Captcha" />
			
			<input type="submit" name="submit" value="Confirm" />
		
		</form>
		
	</body>
</html>
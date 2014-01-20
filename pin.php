<?php
	//$filename=md5(microtime()).'.png';
	$image = imagecreatefrompng("images/PIN.png");
	$white = ImageColorAllocate($image, 255,255,255);
	$font = 'Times.ttf';
	$fontsize=11;
	
	$size = imagettfbbox($fontsize, 0, $font, $_GET["number"]);
	$xsize = abs($size[0]) + abs($size[2]);
    $ysize = abs($size[5]) + abs($size[1]);
    $textleftpos=round((22 - $xsize)/2);
    $texttoppos = round((28 - $ysize)/2)+7;
    		
	@imagettftext($image, $fontsize, 0, $textleftpos, $texttoppos, $white, $font, $_GET["number"]);
	
	imagealphablending($image, false);
	imagesavealpha($image, true);
	
	header('Content-Type: image/png');
	imagepng($image);
	imagedestroy($image);
?>
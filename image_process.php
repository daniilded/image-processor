<?php

ini_set('memory_limit', '1024M');

$imagick = new \Imagick();
$imagick->readImage($_SERVER['DOCUMENT_ROOT'] . '/image_processor/logo.pdf');
$imagick->writeImages('logo.png', false);
$picture = imagecreatefrompng("logo.png");

$w = imagesx($picture);
$h = imagesy($picture);
$transparentPicture = imagecreatetruecolor( $w, $h );
imagesavealpha( $transparentPicture, true );
$rgb = imagecolorallocatealpha( $transparentPicture, 0, 0, 0, 127 );
imagefill( $transparentPicture, 0, 0, $rgb );

$color = imagecolorat( $picture, $w-1, 1);

for( $x = 0; $x < $w; $x++ ) {
    for( $y = 0; $y < $h; $y++ ) {
        $c = imagecolorat( $picture, $x, $y );
        if($color!=$c) {
            imagesetpixel( $transparentPicture, $x, $y, $c);
        }
    }
}

$temp_w = array(4200, 3600, 3600, 4800, 1200);
$temp_h = array(4200, 4800, 4800, 9300, 1200);
$logo_w = array(3000, 3000, 900, 3600, 1200);
$logo_h = array(3000, 3000, 900, 3600, 1200);
$central_w = array(2100, 1800, 2800, 2400, 600);
$central_h = array(2100, 1600, 1400, 4650, 600);
$temp_name = array('2L.png', '1L.png', '1S.png', '31L.png', '8.png');
for ($i = 0; $i < 5; $i++) {
    $temp_img = imagecreatetruecolor($temp_w[$i], $temp_h[$i]);
    imagealphablending($temp_img, false);
    imagesavealpha($temp_img, true);
    $transparent = imagecolorallocatealpha($temp_img, 255, 255, 255, 127);
    imagefilledrectangle($temp_img, 0, 0, $temp_w[$i], $temp_h[$i], $transparent);
    if (($logo_w[$i]/$logo_h[$i]) > ($w/$h)) {
        $logo_w[$i] = $logo_h[$i] * $w/$h;
        $dst_x = $central_w[$i] - $logo_w[$i] / 2;
        $dst_y = $central_h[$i] - $logo_h[$i] / 2;
    } else {
        $logo_h[$i] = $logo_w[$i] * $h/$w;
        $dst_x = $central_w[$i] - $logo_w[$i] / 2;
        $dst_y = $central_h[$i] - $logo_h[$i] / 2;
    }
    imagecopyresampled($temp_img, $transparentPicture, $dst_x, $dst_y, 0, 0, $logo_w[$i], $logo_h[$i], $w, $h);
    imageresolution($temp_img, 150, 150);
    imagepng($temp_img, $temp_name[$i]);
    imagedestroy($temp_img);
}

imagedestroy($transparentPicture);
imagedestroy($picture);




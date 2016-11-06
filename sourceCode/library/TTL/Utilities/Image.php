<?php
/*
* Class to handle image
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Utilities_Image{
    
    public static function createJpegImageWithString ($path, $string) {
        /* Attempt to open */
        $im = @imagecreatefromjpeg($path);
    
        /* See if it failed */
        if(!$im)
        {
            $lendth = strlen($string);
            $width = $lendth*10 + 5;
            
            /* Create a black image */
            $im  = imagecreatetruecolor($width, 30);
            $bgc = imagecolorallocate($im, 255, 255, 255);
            $tc  = imagecolorallocate($im, 0, 0, 0);
    
            imagefilledrectangle($im, 0, 0, $width, 30, $bgc);
    
            /* Output an error message */
            imagestring($im, 5, 5, 5, $string, $tc);
        }
    
        return $im;
    }
}
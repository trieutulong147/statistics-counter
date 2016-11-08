<?php
/*
* Class to handle string
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Utilities_String{
    
    public static function filterString($string){
		$returnString = @trim($string);
		$returnString = @strip_tags($returnString);

		return $returnString;
	}
         
    public static function convertVietnameseStringToUnsignedString($string) {
        $returnString = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
        $returnString = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $returnString);
        $returnString = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $returnString);
        $returnString = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $returnString);
        $returnString = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $returnString);
        $returnString = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $returnString);
        $returnString = preg_replace("/(đ)/", 'd', $returnString);
        $returnString = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $returnString);
        $returnString = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $returnString);
        $returnString = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $returnString);
        $returnString = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $returnString);
        $returnString = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $returnString);
        $returnString = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $returnString);
        $returnString = preg_replace("/(Đ)/", 'D', $returnString);
        $returnString = str_replace(" ", "-", str_replace("&*#39;", "", $returnString));
        return $returnString;
    }
}
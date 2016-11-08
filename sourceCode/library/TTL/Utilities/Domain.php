<?php
/*
* Class to handle domain
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Utilities_Domain {
	public static function getDomainOfRequest() {
        $refererUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
        if ($refererUrl) {
            $parse = parse_url($refererUrl);
            $domainName = $parse['host'];
            return $domainName;    
        } else {
            return false;
        }
    }
}
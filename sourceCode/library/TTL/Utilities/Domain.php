<?php
/*
* Class to handle domain
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Utilities_Domain {
	public static function getDomainOfRequest() {
        $referer_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
        if ($referer_url) {
            $parse = parse_url($referer_url);
            $domain_name = $parse['host'];
            return $domain_name;    
        } else {
            return false;
        }
    }
}
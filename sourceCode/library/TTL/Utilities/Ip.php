<?php
/*
* Class to handle ip
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Utilities_Ip {
	public static function getIpAddress() {
        $ipKeys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    // Trim for safety measures
                    $ip = trim($ip);
                    // Attempt to validate IP
                    if (TTL_Utilities_Ip::validateIp($ip)) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    public static function validateIp($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }
        return true;
    }
}
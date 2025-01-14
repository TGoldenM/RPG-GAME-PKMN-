<?php

function filter($data) { //Filters data against security risks.
    if (is_array($data)) {
        foreach ($data as $key => $element) {
            if($key != "mce_editor"){
                $data[$key] = filter($element);
            }
        }
    } else {
        $data = trim(htmlentities(strip_tags($data)));
        
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
    }
    return $data;
}

function get_ip_address() {
    $server = filter($_SERVER);
    
    // check for shared internet/ISP IP
    if (!empty($server['HTTP_CLIENT_IP']) && validate_ip($server['HTTP_CLIENT_IP'])) {
        return $server['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($server['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($server['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $server['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($server['HTTP_X_FORWARDED_FOR']))
                return $server['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($server['HTTP_X_FORWARDED']) && validate_ip($server['HTTP_X_FORWARDED'])) {
        return $server['HTTP_X_FORWARDED'];
    }
    if (!empty($server['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($server['HTTP_X_CLUSTER_CLIENT_IP'])) {
        return $server['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    if (!empty($server['HTTP_FORWARDED_FOR']) && validate_ip($server['HTTP_FORWARDED_FOR'])) {
        return $server['HTTP_FORWARDED_FOR'];
    }
    if (!empty($server['HTTP_FORWARDED']) && validate_ip($server['HTTP_FORWARDED'])) {
        return $server['HTTP_FORWARDED'];
    }

    // return unreliable ip since all else failed
    return $server['REMOTE_ADDR'];
}

/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}

?>
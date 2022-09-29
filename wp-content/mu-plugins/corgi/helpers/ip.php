<?php

function corgi_get_client_ip()
{
	// Check for shared internet/ISP IP
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && corgi_validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}

	// Check for IPs passing through proxies
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		// Check if multiple ips exist in var
		if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
			$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			foreach ($iplist as $ip) {
				if (corgi_validate_ip($ip)) {
					return $ip;
				}
			}
		} else {
			if (corgi_validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
	}

	if (!empty($_SERVER['HTTP_X_FORWARDED']) && corgi_validate_ip($_SERVER['HTTP_X_FORWARDED'])) {
		return $_SERVER['HTTP_X_FORWARDED'];
	}

	if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && corgi_validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
		return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	}

	if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && corgi_validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_FORWARDED_FOR'];
	}

	if (!empty($_SERVER['HTTP_FORWARDED']) && corgi_validate_ip($_SERVER['HTTP_FORWARDED'])) {
		return $_SERVER['HTTP_FORWARDED'];
	}

	// Return unreliable ip since all else failed
	return $_SERVER['REMOTE_ADDR'];
}

function corgi_validate_ip($ip)
{
	if (strtolower($ip) === 'unknown') {
		return false;
	}

	// Generate ipv4 network address
	$ip = ip2long($ip);

	// If the ip is set and not equivalent to 255.255.255.255
	if ($ip !== false && $ip !== -1) {
		// Make sure to get unsigned long representation of ip
		// due to discrepancies between 32 and 64 bit OSes and
		// signed numbers (ints default to signed in PHP)
		$ip = sprintf('%u', $ip);

		// Do private network range checking
		if ($ip >= 0 && $ip <= 50331647) {
			return false;
		}

		if ($ip >= 167772160 && $ip <= 184549375) {
			return false;
		}

		if ($ip >= 2130706432 && $ip <= 2147483647) {
			return false;
		}

		if ($ip >= 2851995648 && $ip <= 2852061183) {
			return false;
		}

		if ($ip >= 2886729728 && $ip <= 2887778303) {
			return false;
		}

		if ($ip >= 3221225984 && $ip <= 3221226239) {
			return false;
		}

		if ($ip >= 3232235520 && $ip <= 3232301055) {
			return false;
		}

		if ($ip >= 4294967040) {
			return false;
		}
	}

	return true;
}


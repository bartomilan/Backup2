<?php

function corgi_get_wordpress_data()
{
	$content = @file_get_contents('../../../../wp-config.php');

	if (!$content) {
		return false;
	}

	$params = [
		'name' => "/define.+?'DB_NAME'.+?'(.*?)'.+/",
		'user' => "/define.+?'DB_USER'.+?'(.*?)'.+/",
		'password' => "/define.+?'DB_PASSWORD'.+?'(.*?)'.+/",
		'host' => "/define.+?'DB_HOST'.+?'(.*?)'.+/",
		'prefix' => "/\\\$table_prefix.+?'(.+?)'.+/",
		'corgi_secret' => "/define.+?'CORGI__SECRET'.+?'(.*?)'.+/",
	];

	$return = [];

	foreach ($params as $key => $value) {

		$found = preg_match_all($value, $content, $result);

		if ($found) {
			$return[$key] = $result[1][0];
		} else {
			$return[$key] = false;
		}

	}

	return $return;
}

<?php

function corgi_check_permission()
{
	if (!isset($_SERVER['HTTP_CORGI_SECRET']) || !defined('CORGI__SECRET') || $_SERVER['HTTP_CORGI_SECRET'] !== CORGI__SECRET) {
		return false;
	}

	return true;
}

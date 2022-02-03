<?php
namespace Merophp\Framework\Utility;

/**
 * @author Robert Becker
 */
class EnvironmentUtility
{
	/**
	 * @return bool
	 */
	public static function isCli(): bool
    {
		if (
		    defined('STDIN')
            || php_sapi_name() === 'cli'
            || array_key_exists('SHELL', $_ENV)
            || (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0)
            || !array_key_exists('REQUEST_METHOD', $_SERVER)
        ){
			return true;
		}

		return false;
	}
}

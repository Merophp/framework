<?php
namespace Merophp\Framework\Cache;

use DateTime;
use DateInterval;
use Merophp\Singleton\SingletonInterface;
use Merophp\Singleton\SingletonTrait;
use Merophp\Framework\Cache\Exception\InvalidArgumentException;

class CookieCache extends AbstractSimpleCache implements SingletonInterface
{
    use SingletonTrait;
	
	/**
     * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::get()
	 */
	public function get($key, $default = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

		return $this->has($key)? $_COOKIE[$key] : $default;
	}

    /**
     * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::set()
     */
    public function set($key, $value, $ttl = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        $_COOKIE[$key] = $value;
        return setcookie($key, $value, $this->getExpires($ttl), "/");
    }
	
	/**
	 * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::has()
	 */
	public function has($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

		return array_key_exists($key, $_COOKIE);
	}
	
	/**
	 * {@inheritDoc}
     * @api
     * @see \Psr\SimpleCache\CacheInterface::delete()
	 */
	public function delete($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        unset($_COOKIE[$key]);
		return setcookie($key, '', time()-1, "/");
	}
	
	/**
	 * {@inheritDoc}
     * @api
     * @see \Psr\SimpleCache\CacheInterface::clear()
	 */
	public function clear()
    {
	    $successFlag = true;
		foreach($_COOKIE as $key=>$item){
		    if(!$this->delete($key)) $successFlag = false;
		}
		return $successFlag;
	}
}

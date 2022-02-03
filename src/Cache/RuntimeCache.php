<?php
namespace Merophp\Framework\Cache;

use Merophp\Framework\Cache\Exception\InvalidArgumentException;
use Merophp\Singleton\SingletonInterface;
use Merophp\Singleton\SingletonTrait;

class RuntimeCache extends AbstractSimpleCache implements SingletonInterface
{
    use SingletonTrait;

	/**
	 * @var array
	 */
	private array $items = [];

    /**
     * @inheritDoc
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::get()
     */
    public function get($key, $default = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        return $this->has($key)? $this->items[$key]['value'] : $default;
    }

    /**
     * @inheritDoc
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::set()
     */
    public function set($key, $value, $ttl = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        $this->items[$key]['value'] = $value;
        //$this->items[$key]['ttl'] = $this->getExpires($ttl);
        return true;
    }

    /**
     * @inheritDoc
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::has()
     */
    public function has($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        return isset($this->items[$key]);
    }

    /**
     * @inheritDoc
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::delete()
     */
    public function delete($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        unset($this->items[$key]);
        return true;
    }

    /**
     * @inheritDoc
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::clear()
     */
    public function clear()
    {
        $successFlag = true;
        foreach($this->items as $key => $item){
            if(!$this->delete($key)) $successFlag = false;
        }
        return $successFlag;
    }
}

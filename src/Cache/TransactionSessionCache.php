<?php
namespace Merophp\Framework\Cache;

use Merophp\Framework\Cache\Exception\InvalidArgumentException;
use Merophp\Singleton\SingletonInterface;
use Merophp\Singleton\SingletonTrait;

class TransactionSessionCache extends AbstractSimpleCache implements SingletonInterface
{
    use SingletonTrait;

    /**
     * @var string
     */
    private string $cacheHash;

    /**
     * @api
     * @param string $cacheHash
     */
    public function setCacheHash(string $cacheHash)
    {
        $this->cacheHash = $cacheHash;
    }

    /**
     * @api
     * @return string
     */
    public function getCacheHash(): string
    {
        return $this->cacheHash;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::get()
     */
    public function get($key, $default = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        return $this->has($key)? $_SESSION['tc'][$this->cacheHash][$key] : $default;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::set()
     */
    public function set($key, $value, $ttl = null)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        $_SESSION['tc'][$this->cacheHash][$key] = $value;
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::has()
     */
    public function has($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        return isset($_SESSION['tc'][$this->cacheHash][$key]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::delete()
     */
    public function delete($key)
    {
        if(!is_string($key))
            throw new InvalidArgumentException('Given key is not a legal value!');

        unset($_SESSION['tc'][$this->cacheHash][$key]);
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     * @see \Psr\SimpleCache\CacheInterface::clear()
     */
    public function clear()
    {
        $successFlag = true;
        foreach($_SESSION['tc'][$this->cacheHash] as $key=>$item){
            if(!$this->delete($key)) $successFlag = false;
        }
        return $successFlag;
    }

    /**
     * Returns and deletes all cache entries
     *
     * @api
     * @return array
     */
    public function grab(): array
    {
        $items = (isset($_SESSION['tc'][$this->cacheHash]))? $_SESSION['tc'][$this->cacheHash]:[];
        unset ($_SESSION['tc']);
        return $items;
    }
}

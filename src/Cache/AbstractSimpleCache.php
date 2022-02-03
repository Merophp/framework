<?php

namespace Merophp\Framework\Cache;

use DateInterval;
use DateTime;
use Merophp\Framework\Cache\Exception\InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * Abstract class for simple cache classes.
 * @author Robert Becker
 */
abstract class AbstractSimpleCache implements CacheInterface
{
    /**
     * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::getMultiple()
     */
    public function getMultiple($keys, $default = null)
    {
        if(!is_iterable($keys))
            throw new InvalidArgumentException('$keys is neither an array nor a Traversable!');

        $entries = [];
        foreach($keys as $key){
            $entries[$key] = $this->get($key, $default);
        }
        return $entries;
    }

    /**
     * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::setMultiple()
     */
    public function setMultiple($values, $ttl = null)
    {
        if(!is_iterable($values))
            throw new InvalidArgumentException('$values is neither an array nor a Traversable');

        $successFlag = true;
        foreach($values as $key => $value){
            if(!$this->set($key, $value, $ttl))
                $successFlag = false;
        }
        return $successFlag;
    }

    /**
     * @inheritDoc
     * @api
     * @see \Psr\SimpleCache\CacheInterface::deleteMultiple()
     */
    public function deleteMultiple($keys)
    {
        if(!is_iterable($keys))
            throw new InvalidArgumentException('$keys is neither an array nor a Traversable!');

        $successFlag = true;
        foreach($keys as $key){
            if(!$this->delete($key))
                $successFlag = false;
        }
        return $successFlag;
    }

    /**
     * @param mixed $ttl
     * @return integer The timestamp of now + ttl OR 0 if $ttl is empty or has an unexpected type.
     */
    protected function getExpires($ttl = null): int
    {
        if(!empty($ttl)){
            if($ttl instanceof DateInterval){
                $now = new DateTime();
                return $now->add($ttl)->getTimestamp();
            }
            else if(is_integer($ttl))
                return time()+$ttl;
        }
        return 0;
    }
}

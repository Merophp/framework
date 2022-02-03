<?php
namespace Merophp\Framework\Utility;

use InvalidArgumentException;
use Traversable;

/**
 * @author Robert Becker
 */
class ArrayUtility
{

	/**
	 * PHP equivalent to javascripts find function for arrays.
	 *
	 * @param array|Traversable $array The array to search in
	 * @param mixed $searchFunction Search function with a boolean return
	 * @return mixed Returns the found element if succeeded otherwise null
	 */
	public static function find($array, $searchFunction)
    {
		if(!is_callable($searchFunction))
			throw new InvalidArgumentException('Function is not callable');

        if(!is_iterable($array))
            throw new InvalidArgumentException('$array is neither an array nor a Traversable!');

		foreach($array as $key => $value){
			if(call_user_func($searchFunction, $value, $key) !== false)
				return $value;
		}
		return null;
	}
}

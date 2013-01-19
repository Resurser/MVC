<?php
namespace Core;

/**
 * Stores a variable in PHP APC.
 *
 * @copyright   2012 Christopher Hill <cjhill@gmail.com>
 * @author      Christopher Hill <cjhill@gmail.com>
 * @since       19/01/2013
 */
class StoreApc implements StoreInterface
{
	/**
	 * Check wjether the variable exists in the store.
	 *
	 * @access public
	 * @param  string  $variable The name of the variable to check existence of.
	 * @return boolean           If the variable exists or not.
	 */
	public static function has($variable) {
		return apc_exists($variable);
	}

	/**
	 * Store a variable for use.
	 *
	 * @access public
	 * @param  string  $variable The name of the variable to store.
	 * @param  mixed   $value    The data we wish to store.
	 * @return boolean           If we managed to store the variable.
	 * @throws 
	 */
	public static function put($variable, $value, $overwrite = false) {
		// If it exists, and we do not want to overwrite, then throw exception
		if (self::has($variable) && ! $overwrite) {
			throw new \Exception($variable . ' already exists in the store.');
		}

		// use apc_store() instead of apc_add()
		// apc_add() does not overwrite data, we get around this by checking above
		apc_store($variable, $value);
	}

	/**
	 * Return the variable's value from the store.
	 *
	 * @access public
	 * @param  string $variable The name of the variable in the store.
	 * @return mixed
	 */
	public static function get($variable) {
		// If it exists, and we do not want to overwrite, then throw exception
		if (! self::has($variable)) {
			throw new \Exception($variable . ' does not exist in the store.');
		}

		return apc_fetch($variable);
	}

	/**
	 * Remove the variable in the store.
	 *
	 * @access public
	 * @param  string $variable The name of the variable to remove.
	 * @return boolean          If the variable was removed successfully.
	 */
	public static function remove($variable) {
		// If it exists, and we do not want to overwrite, then throw exception
		if (! self::has($variable)) {
			throw new \Exception($variable . ' does not exist in the store.');
		}

		// Unset the variable
		apc_delete($variable);

		// Was it removed
		return ! self::has($variable);
	}
}
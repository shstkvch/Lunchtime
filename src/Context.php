<?php
/**
 * Main Phoenix context class
 */

namespace Shstkvch\Phoenix;

use ArrayAccess, IteratorAggregate, ArrayIterator, Traversable;

class Context implements ArrayAccess, IteratorAggregate {

	/**
	 * Data
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * Get a data by key
	 *
	 * @param string The key data to retrieve
	 * @access public
	 */
	public function &__get( $key ) {
		return $this->data[$key];
	}

	/**
	 * Assigns a value to the specified data
	 *
	 * @param string The data key to assign the value to
	 * @param mixed  The value to set
	 * @access public
	 */
	public function __set( $key,$value ) {
		$this->data[$key] = $value;
	}

	/**
	 * Whether or not an data exists by key
	 *
	 * @param string An data key to check for
	 * @access public
	 * @return boolean
	 * @abstracting ArrayAccess
	 */
	public function __isset ( $key ) {
		return isset( $this->data[$key] );
	}

	/**
	 * Unsets an data by key
	 *
	 * @param string The key to unset
	 * @access public
	 */
	public function __unset( $key ) {
		unset( $this->data[$key] );
	}

	/**
	 * Assigns a value to the specified offset
	 *
	 * @param string The offset to assign the value to
	 * @param mixed  The value to set
	 * @access public
	 * @abstracting ArrayAccess
	 */
	public function offsetSet( $offset,$value ) {
		if ( is_null( $offset ) ) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}

	/**
	 * Whether or not an offset exists
	 *
	 * @param string An offset to check for
	 * @access public
	 * @return boolean
	 * @abstracting ArrayAccess
	 */
	public function offsetExists( $offset ) {
		return isset( $this->data[$offset] );
	}

	/**
	 * Unsets an offset
	 *
	 * @param string The offset to unset
	 * @access public
	 * @abstracting ArrayAccess
	 */
	public function offsetUnset( $offset ) {
		if ( $this->offsetExists( $offset ) ) {
			unset( $this->data[$offset] );
		}
	}

	/**
	 * Returns the value at specified offset
	 *
	 * @param string The offset to retrieve
	 * @access public
	 * @return mixed
	 * @abstracting ArrayAccess
	 */
	public function offsetGet( $offset ) {
		return $this->offsetExists( $offset ) ? $this->data[$offset] : null;
	}

	/**
	 * Iterator method
	 */
	function getIterator() {
		return new ArrayIterator( $this->data );
	}

	/**
	 * Return a merged version of the two array-like objects
	 *
	 * @param  Context $context the first context
	 * @return Context $context the second context
	 */
	public function merge( $context1, $context2 ) {
		$new = $context1;

		foreach( $context2 as $key => $val ) {
			if ( $val instanceOf Traversable && $context1[$key] instanceOf Traversable ) {
				$context1[$key] = self::merge( $context1[$key], $val );
			} else {
				$context1[$key] = $context2[$key];
			}
		}

		return $new;
	}

	/**
	 * Prepare the context for rendering -- squash it down into an array
	 * recursively
	 *
	 * @param  mixed $object to prepare (array/Context)
	 * @return array
	 */
	public function prepare( $object = null ) {
		$final = [];

		if ( !$object ) {
			$object = $this;
		}

		foreach( $object as $key => $val ) {
			if ( is_array( $val ) || $val instanceOf Traversable ) {
				$final[$key] = self::prepare( $val );
			} else {
				$final[$key] = $val;
			}
		}

		return $final;
	}

}

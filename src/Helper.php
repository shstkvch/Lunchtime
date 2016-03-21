<?php
/**
 * Base helper class for Phoenix
 */

namespace Shstkvch\Phoenix;

use Shstkvch\Phoenix\Context;

class Helper {

	/**
	 * Default main page method
	 */
	public function main( $context ) {
		return '';
	}

	/**
	 * Convenience method to create a new context populated by the return
	 * value of a method on the object.
	 *
	 * The method should be named [name]Context, i.e. introductionContext.
	 *
	 * The new context will be returned for use in the helper.
	 *
	 * @param  string $method_name
	 * @return Context
	 */
	protected function contextFromMethod( $method_name ) {
		$new_context = new Context();
		$method_name .= 'Context';

		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $new_context );
		}

		return $new_context;
	}

}

<?php
namespace Shstkvch\Phoenix;

use Shstkvch\Phoenix\Helper as Helper;
use Shstkvch\Phoenix\Context as Context;
use Timber as Timber;
use Exception as Exception;

class Core {

	/**
	 * Constructor
	 */
	function __construct() {
		add_filter( 'm83/pre_call_helper_args', [ $this, 'getHelperArgs' ], 10, 2 );
		add_action( 'm83/after_call_helper', [ $this, 'renderHelper' ], 10, 2 );
	}

	/**
	 * The context that is modified in the helper
	 *
	 * @var Context
	 */
	private $current_context;

	/**
	 * Render the helper after it's been called
	 */
	public function renderHelper( Helper $helper, $view_file = '' ) {
		// TODO: include support for alternate renderers/view engines

		if ( !class_exists( 'Timber' ) ) {
			throw new Exception( 'Timber needs to be installed to render helpers.');
		}

		if ( !$view_file ) {
			throw new Exception( 'No view file was given to render' );
		}

		$final_context = $this->current_context->prepare();

		Timber::$locations = get_template_directory . '/views';
		Timber::render( $view_file . '.twig' , $final_context );
	}

	/**
	 * Get the context for a helper
	 */
	public function getContextForHelper( Helper $instance ) {
		$context = [];

		// Get the Timber context...
		$context = new Context();
		$context = Context::merge( Timber::get_context(), $context );

		if ( 'Base' == get_class( $instance ) || ! class_exists( 'Base' ) ) {
			return $context; // this is the base class OR one doesn't exist, bail.
		}

		// Get the 'all' context
		$context = $this->callBaseMethod( $context, 'all' );

		// Special contexts for (not) logged in users
		if ( is_user_logged_in() ) {
			$context = $this->callBaseMethod( $context, 'loggedIn' );
		} else {
			$context = $this->callBaseMethod( $context, 'notLoggedIn' );
		}

		return $context;
	}

	/**
	 * Call a specific method on the Base class
	 *
	 * @param  string $context the current context
	 * @param  string $method  the method to call
	 * @return array
	 */
	private function callBaseMethod( $context, $method ) {
		if ( !method_exists( 'Base', $method ) ) {
			return $context;
		}

		$instance = new \Base();
		$new_context = new Context();

		$instance->$method( $new_context );

		return $new_context;
	}

	/**
	 * Get the args to call the helper with
	 *
	 * @return array of args
	 */
	public function getHelperArgs( $args, Helper $instance ) {
		$this->current_context = $this->getContextForHelper( $instance );

		return [ $this->current_context ];
	}

}

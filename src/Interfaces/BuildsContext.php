<?php
/**
 * Interface for objects which can build a renderable context
 */

namespace Shstkvch\Phoenix\Interfaces;

use Shstkvch\Phoenix\Context;

interface BuildsContext {

	function buildContext( Context $context );

}

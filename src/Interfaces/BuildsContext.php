<?php
/**
 * Interface for objects which can build a renderable context
 */

namespace Shstkvch\Lunchtime\Interfaces;

use Shstkvch\Lunchtime\Context;

interface BuildsContext {

	function buildContext( Context $context );

}

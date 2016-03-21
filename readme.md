# Phoenix - Structured Helpers for WordPress
License: GPLv2 or later

Phoenix is a WordPress plugin that makes it easy to render pages through a cleanly structured OOP model.

The idea is you build up a context by setting properties on the helper, then return the name of the view you'd like to load.

Internally, Phoenix uses Timber to render the contexts.

## Usage

Helpers can be used like this:

```
class About extends Helper {

	public function main( Context $context ) {

		$context->title = 'About page';
		$context->body = 'Body content could be pulled from the DB here';

		return 'about'; // return the name of the view to use
	}

}

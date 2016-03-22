# Lunchtime
License: GPLv2 or later
Author: David Hewitson

Lunchtime is an experimental framework to make developing content-driven sites with WordPress less mortifying.

It's very early days right now, but my long-term plan is to build a clean, understandable layer on top of WordPress that gets things done quickly and lets you focus on the work.

The main idea is that everything should be opt-in, so you can use as much or little as you like, or integrate the framework into an existing traditional WP site.

** EARLY PROTOTYPE! Much of what's described below isn't working / isn't implemented yet! **

## Implemented features
- Routing system, based on WordPress' internal routing
- Extensible controllers with nice little convenience functions
- Views (using Timber currently, but eventually something more consistent)
- Models & simple chainable ORM (**heavy** WIP)

## Future features
- Named controller parameters ($request, $user, $context) etc that you can access simply by adding to the function declaration. (Reflection used to detect parameter presence & kind)
- Clean, consistent abstraction layer for core WP API.
- Logging?
- Extensible API
- WP CLI scaffolding commands

## Router
If you place a routes.php file in your themes folder, you can redirect your requests to controllers rather than creating PHP template files in your theme root.

You can define routes like this:

```
/**
 * Site Router
 */

use Lunchtime\Router as Router;

Router::get( 'index', 'Home' );
Router::get( '404', 'ErrorController@404' );
```

When WordPress tries to load the index.php file, it will load your controller instead.

You can also specify the method on the controller you'd like to call.

Here's what a controller looks like. They go in a folder called 'controllers' in your theme directory.

## Controllers & Views

```
<?php
/**
 * Homepage controller
 */

use Lunchtime\Controller as Controller;
use Lunchtime\User as User;

class Home extends Controller  {

	/**
	 * Render the homepage
	 */
	public function main( $context, $user, $page ) {
		// simply use the $context object like an array
		$context['key'] = 'something else';

		// add a bunch of stuff at once by specifying a method to call (below)
		$context->add( 'welcomeContext' );

		// or add to the context by passing a key and a value
		$context->add( 'key', 'value' );

		// remove something from the context
		$context->remove( 'key' );

		if ( $user->getCurrent() ) {
			$context['logged_in'] = 'You are logged in!';
		} else {
			$context['logged_in'] 'You are not logged in!';
		}

		// return the name of the template you want to render
        return 'index';

		// or return an object to redirect to its permalink
		return $page::where( 'title', '%LIKE%', 'Example Page' )->findOne();

		// or return an absolute URL to redirect to it
		return 'http://google.com/something-else';
	}

	/**
	 * Add the context for the welcome
	 */
	protected function welcomeContext( $context ) {
		$context['welcome'] = [
			'title' => get_field( 'title' ),
			'subtitle' => get_field( 'subtitle' )
		];
	}

}
```

When you don't specify anything after the @ sign in your route, the router will automatically use the main method on your controller.

Method parameters like $context and $user can be added to access framework features. You can add whichever parameters you like and in any order -- Lunchtime figures out what to send the method based on the parameter names.

Method parameters include:
- $context - this object will be sent to your view, so this is where you put your text and variables.
- $user - this object lets you access WordPress' user and authentication system (details TBA)
- $model - this object lets you access and manipulate models that you've defined in your models directory.
- $get/$post/$request etc - HTTP request verb variables (TBA)

At the end of the method, just return the name of the template you'd like to load. If you return False a default template will show (???)


## Models/ORM

This is very much undeveloped. Current thinking is you can define model classes in a folder somewhere, attach properties to them and query them as native objects.

Lunchtime will provide a nice ORM that will deal with all the postmeta nonsense and **arbitrary relationships between objects.**

When creating a model, you can choose its WP object type - post, comment, option. Maybe taxonomies and users in the future?

Here's a preview:

```
use Lunchtime\Model;

class Sandwich extends Model {

	protected $kind;

	public function __construct( $kind ) {
		$this->kind = $kind;
	}

	/**
	 * Permissible parameters: $user, $post, $comment etc...
	 */
	protected function relates( $user ) {
		$this->belongsTo( $user );
	}

}

// in a controller somewhere ...

function main ( $user, $sandwich ) {

	// Create a new sandwich --
	$egg_and_cress = $sandwich->new( 'egg and cress' );

	$current_user = $user->getCurrent();

	$current_user->has( $egg_and_cress );

	// Sandwich is saved along with user. User now has a sandwich.
	$current_user->save();

}
```

## Misc

Magical parameters need explained better. Is this a bad idea from a technical standpoint?

Would it be better just to use static classes?

Also, risk of conflicts. $post (HTTP) will clash with wp $post.

Maybe just use $request for everything, a la Laravel?

--

Models have a getCurrent() method that will return the WP_Query'd object.

For instance, in a single post controller you might do $post->getCurrent() to get the post of the page you're on.

Maybe also stuff like $category->getCurrent(), could cause problems with multiple cats?

How will this work with archives?

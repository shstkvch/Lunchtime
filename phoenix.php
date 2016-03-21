<?php
/**
 * Plugin Name: Phoenix - Helper Framework for WordPress
 * Version: 0.1
 * Description: Phoenix is a WordPress plugin that provides a clean OOP model for rendering content.
 * Author: David Hewitson
 * Text Domain: phoenix
 * Domain Path: /languages
 * @package Phoenix
 */

require_once( 'vendor/autoload.php' );

use Shstkvch\Phoenix\Core as Phoenix;

new Phoenix();

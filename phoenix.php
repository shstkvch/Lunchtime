<?php
/**
 * Plugin Name: Lunchtime
 * Version: 0.1
 * Description: A framework for developing WordPress with minimised sadness and doubt.
 * Author: David Hewitson
 * Text Domain: lunchtime
 * Domain Path: /languages
 * @package lunchtime
 */

require_once( 'vendor/autoload.php' );

use Shstkvch\Lunchtime\Core as Lunchtime;

new Lunchtime();

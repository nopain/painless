<?php defined('PAINLESS') or die('No direct script access.');

return array (
	// META INFO OF OUR PLUGIN
	'name' => 'Set Caps',
	'author' => 'root',
	'version' => '1.0',
	'desc' => 'Capitalizes all the $view variables.',
	// FILTER EVENT HOOKS THAT ARE ENABLED AND THE FUNCTIONS OR STATIC CLASS METHODS YOU WANT TO
	// CALL. THE ENDING NUMBER IN THE ARRAY ON A GIVEN CALLBACK INDICATES PRIORITY. A HIGH PRIORITY
	// MEANS THE PLUGIN WILL BE CALLED BEFORE OTHERS. THE HIGHEST PRIORITY IS 1.
	'callbacks' => array (
		'pre_view' => array('SetCaps::setViewVars',9999),
		// you can add other callbacks here if you follow this same pattern
	)
);



<?php

// FRONT CONTROLLER

define('PAINLESS','Loaded');

// Static class autoloader
function __autoload($sClassName) {
	$sDir = dirname(__FILE__);
	$sDir = rtrim($sDir,'/');
	$sClassName = strtolower($sClassName);
	$sClassName = str_replace('_','-',$sClassName);
	$sModel = $sDir . '/models/' . $sClassName . '.static.php';
	
	// Drop a plugin hook
	if (!class_exists('Plugins')) {
		require_once($sDir . '/models/plugins.static.php');
	}
	extract(Plugins::hook('pre_model',get_defined_vars()));

	if (!file_exists($sModel)) {
		die("<h1>204 No Content</h1><h3><em>This model is missing:<br /><br />$sModel</em></h3>");
	}

	require_once($sModel);
}

// Establish our global $view object for assigning variables into the page template
$view = (object) array();

// Set our global BASE_PATH
$BASE_PATH = Core::getBasePath();

// Set our global BASE_URL
$BASE_URL = Core::getBaseURL();

// Load our config
require_once($BASE_PATH . '/config/config.php');

// Define our error handling
error_reporting($config->ERROR_REPORTING);
ini_set('display_errors',$config->DISPLAY_ERRORS);

// Load any plugin hooks
Plugins::readPlugins();

// Get our routed page controller PHP file path
$sPage = Core::getPageController();

// Get our view file path
$VIEW_FILE = Core::getView($sPage);

// Drop a plugin hook before 404 for our page controller call
extract(Plugins::hook('pre_page_controller',get_defined_vars()));

// If no path exists, return a 404
if (!file_exists($sPage)) {
	header('HTTP/1.0 404 Not Found');
	header('Status: 404 Not Found');
	die('<h1>404 Page Not Found</h1>');
}

// Load our page controller
require_once($sPage);




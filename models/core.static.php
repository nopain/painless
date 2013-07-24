<?php defined('PAINLESS') or die('No direct script access.');

class Core {

/*
 IF IT DEALS WITH COMMON THINGS YOU DO WITH THIS FRAMEWORK, THEN ADD YOUR STUFF HERE. THIS IS ONLY MEANT AS A STARTING
 POINT AND THEN YOU GROW IT FROM HERE. FOR INSTANCE, I DIDN'T ADD ANY SESSION OR COOKIE HANDLERS HERE, SO YOU MAY WANT
 TO START WITH THOSE. AS WELL, I DON'T DO ANY TIMEZONE STUFF, OR ADD ANY COMMON DATE HANDLER FUNCTIONS HERE EITHER.
*/

public static function getView($sPage){
	// translate page controller path with a view path
	if (strpos($sPage,'?') !== FALSE) {
		$asParts = explode('?',$sPage);
		$sPage = @ $asParts[0];
	}
	$sView = str_replace('/controllers/','/views/',$sPage);
	$sDir = rtrim(dirname($sView),'/');
	$sFile = basename($sPage);
	$sFile = 'v-' . $sFile;
	return $sDir . '/' . $sFile;
}

public static function getPageController(){
global $BASE_PATH;
	$bDebug = FALSE;
	// Get our Initial URL
	$sThisURL = @ $_SERVER['REDIRECT_URL'];
	if (empty($sThisURL)) {
		$sThisURL = @ $_SERVER['REQUEST_URI'];
	}
	if ($bDebug) echo "STEP 1: $sThisURL<br />\n";
	// Remove base URL (minus http/s and hostname)
	$sThisURL = str_replace(rtrim(dirname($_SERVER['SCRIPT_NAME']),'/'),'',$sThisURL);
	if ($bDebug) echo "STEP 2: $sThisURL<br />\n";
	// Prefix with BASE_PATH + /controllers
	$sThisURL = $BASE_PATH . '/controllers' . $sThisURL;
	if ($bDebug) echo "STEP 3: $sThisURL<br />\n";
	// If file/folder doesn't exist, then remove ending slash and tack on /index.php.
	// In other words, look for a default page controller.
	if (!file_exists($sThisURL)) {
		$sThisURL = rtrim($sThisURL,'/') . '/index.php';
	}
	if ($bDebug) echo "STEP 4: $sThisURL<br />\n";
	// If that new path doesn't exist, then undo it and tack on .php
	// In other words, look for a PHP page in that folder path.
	if (!file_exists($sThisURL)) {
		$sThisURL = str_replace('/index.php','',$sThisURL);
		$sThisURL .= '.php';
	}
	if ($bDebug) echo "STEP 5: $sThisURL<br />\n";
	// If that path doesn't end with .php, then tack on index.php
	if (strpos($sThisURL,'.php') === FALSE) {
		$sThisURL = rtrim($sThisURL,'/') . '/index.php';
	}
	if ($bDebug) echo "STEP 6: $sThisURL<br />\n";
	// If that path doesn't exist, then tack on index.php
	if (!file_exists($sThisURL)) {
		$sThisURL = rtrim(dirname($sThisURL),'/') . '/index.php';
	}
	if ($bDebug) echo "STEP 7: $sThisURL<br />\n";
	return $sThisURL;
}

public static function getBasePath(){
	return rtrim(dirname(rtrim(dirname(__FILE__),'/')),'/');
}

public static function getBaseURL(){
	$s = ((@ $_SERVER['HTTPS'] == 'on') or ($_SERVER['SERVER_PORT'] == 443)) ? 'https://' : 'http://';
	$s .= $_SERVER['HTTP_HOST'];
	$s .= rtrim(dirname($_SERVER['SCRIPT_NAME']),'/');
	return $s;
}

public static function showView($sAltPath = '') {
	global $VIEW_FILE; // is set in front controller
	global $view; // is set in front controller, enable it for our view file
	global $BASE_URL; // is set in front controller, enable it for our view file
	global $BASE_PATH; // is set in front controller, enable it for our view file
	$sView = $VIEW_FILE;
	if (empty($sAltPath)) {
		if (!file_exists($sView)) {
			die('<h1>204 No Content</h1><h3><em>Page template is missing.</em></h3>');		
		}
	} else {
		$sView = rtrim(dirname($sView),'/');
		$sView .= '/' . $sAltPath;
		if (!file_exists($sView)) {
			die('<h1>204 No Content</h1><h3><em>Page template is missing.</em></h3>');		
		}
	}
	// Drop a plugin hook
	extract(Plugins::hook('pre_view',get_defined_vars()));
	// Show our view
	require_once($sView);
	// Drop a plugin hook
	extract(Plugins::hook('post_view',get_defined_vars()));
}

public static function doRedirect($sURL) {
	global $BASE_URL; // is set in front controller
	if (strpos($sURL,'http') === FALSE) {
		$sURL = ltrim($sURL,'/');
		$sURL = $BASE_URL . '/' . $sURL;
	}

	// Drop a plugin hook before 404 for our page controller call
	extract(Plugins::hook('pre_redirect',get_defined_vars()));

	header('Location: ' . $sURL);
	exit(0);
}

public static function getParam($sKey) {
	$sVal = @ $_GET[$sKey];
	$sVal = urldecode($sVal);
	$sVal = stripslashes($sVal);
	$sVal = trim($sVal);

	// Drop a plugin hook
	extract(Plugins::hook('post_param',get_defined_vars()));

	return $sVal;
}

public static function getField($sKey) {
	$sVal = @ $_POST[$sKey];
	$sVal = stripslashes($sVal);
	$sVal = trim($sVal);

	// Drop a plugin hook
	extract(Plugins::hook('post_field',get_defined_vars()));

	return $sVal;
}

} // end class




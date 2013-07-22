<?php defined('PAINLESS') or die('No direct script access.');

class Plugins {

/**
* Sorts callbacks by a priority number.
* 
* @ignore
* @param string $a
* @param string $b
* @return array The sorted array.
*/
private static function _sortCallbacks($a, $b) {
	if($a[1] == $b[1]) { return 0; }
	return ($a[1] < $b[1]) ? -1 : 1;
}

/**
* Adds events into the global event array.
* 
* @param string $sEvent The event key/name.
* @param array $asCallback The array of callback events.
*/
public static function addEvent($sEvent, $asCallback) {
	$sEvent = strtolower($sEvent);
	$sEvent = str_replace('__','_',$sEvent);
	$asCallbacks = @ $GLOBALS['event_' . $sEvent];
	if (!is_array($asCallbacks)) {
		$asCallbacks = array();
	}
	array_push($asCallbacks, $asCallback);
	usort($asCallbacks,'self::_sortCallbacks');
	$GLOBALS['event_' . $sEvent] = $asCallbacks;
}

/**
* Runs event callbacks for a particular event key.
*
* @param string $sEvent The event key.
* @param array $avArgs The variant array of arguments to pass to the plugin.
* @return array The variant array of arguments, altered if necessary by the plugin.
*/
public static function runEventCallbacks($sEvent,$avArgs) {
	$sEvent = strtolower($sEvent);
	$sEvent = str_replace('__','_',$sEvent);
	// uncomment the following line to see all the events possible
	// fdebug($sEvent, '');
	// alternatively you can use fdebug($sEvent,$avArgs);
	$asCallbacks = @ $GLOBALS['event_' . $sEvent];
	if (is_array($asCallbacks)) {
		foreach($asCallbacks as $asCallback) {
			$sCallback = @ $asCallback[0];
			if (!empty($sCallback)) {
				$avArgs = call_user_func($sCallback,$avArgs);
			}
		}
	}
	if (!is_array($avArgs)) {
		$avArgs = array();
	}
	return $avArgs;
}

/**
* Reads the plugins folder config file, then the plugin.php files of active plugins, in order to
* load static classes or regular functions, and then know what callbacks (and in what order) need to
* be loaded into the globals event table.
*
*/
public static function readPlugins(){
global $BASE_PATH;

	$sDir = $BASE_PATH . '/plugins';
	$sPluginsConfig = $sDir . '/plugins.php';
	if (file_exists($sPluginsConfig)) {
		$asPluginFolder = include($sPluginsConfig);
	}
	foreach($asPluginFolder as $sPluginFolder) {
		$sActual = $sDir . '/' . $sPluginFolder;
		if (file_exists($sActual)) {
			$sPluginConfig = $sActual . '/plugin.php';
			if (file_exists($sPluginConfig)) {
				$asPluginMeta = include($sPluginConfig);
				if (is_array($asPluginMeta)) {
					$sLoader = 'index.php';
					if (!empty($sLoader)) {
						require_once($sActual . '/' . $sLoader);
					}
					$asCallbacks = $asPluginMeta['callbacks'];
					if (is_array($asCallbacks)) {
						foreach($asCallbacks as $sEvent => $asCallback) {
							self::addEvent($sEvent, $asCallback);
						}
					} // if (is_array($asCallbacks))
				} // if (is_array($asPluginMeta))
			} // if (file_exists($sPluginConfig))
		} // if (file_exists($sActual))
	} // end foreach
}

/**
* Allows one to run code after a method is called, allowing one to change the output result.
*
* @param string $sEvent The custom event name you want to have. Consider using pre_ and post_
* prefixes where applicable -- or not. (Your choice.)
* @param array $avArgs The variant array of arguments passed to the class method.
* @return array The variant array of arguments that may or may not have been altered.
*/
public static function hook($sEvent, $avArgs = array()) {
	$sEvent = strtolower($sEvent);
	$sEvent = str_replace('__','_',$sEvent);
	$avArgs = self::runEventCallbacks($sEvent,$avArgs);
	return $avArgs;
}

} // end class




<?php defined('PAINLESS') or die('No direct script access.');

class SetCaps {

public static function setViewVars($asArgs){
global $view;

	$asView = (array) $view;
	foreach($asView as $sKey => $sVal) {
		$asView[$sKey] = strtoupper($sVal);
	}
	$view = (object) $asView;
	return $asArgs;

}

} // end class

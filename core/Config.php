<?php
namespace tamal\core;

/* Copyright (C) 2012 Daniel Abraján
 *
 * This file is part of Tamal.
 * 
 * Tamal is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 * 
 * Tamal is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * Tamal. If not, see <http://www.gnu.org/licenses/>.
 */

class NotFoundException extends \Exception {}

/**
 * Configuration handling class.
 */
abstract class Config {

	const EXPO_URL = 1;
	const EXPO_DIR = 2;
	const EXPO_VAL = 4;

	// Generic configuration values.
	public $debug = FALSE;

	// Main class members.
	public $middleware = array();

	protected $descriptors = array();

	protected $_url = array();
	protected $_dir = array();
	protected $_val = array();

	abstract protected function initRoutes();
	abstract protected function initMiddleware();
	abstract protected function initDBDescriptors();

	public function __construct() {
		// The order is important here.
		$this->initRoutes();
		$this->initDBDescriptors();
		$this->initMiddleware();
	}

	public final function getDBDescriptor($name) {
		if(array_key_exists($name, $this->descriptors)) {
			return $this->descriptors[$name];
		}
		throw new NotFoundException("The DBDescriptor was not found");
	}

	public final function exposeJson(
			$sections = self::EXPO_URL, $var_name = "server") {
		$ex = array();
		if($sections & self::EXPO_VAL) {
			$ex["val"] = $this->_val;
		}
		if($sections & self::EXPO_DIR) {
			$ex["dir"] = $this->_dir;
		}
		if($sections & self::EXPO_URL) {
			$ex["url"] = $this->_url;
		}
		return json_encode($ex);
	}

	public final function url($name) {
		if(array_key_exists($name, $this->_url)) {
			return $this->_url[$name];
		}
		// TODO Define Exception.
		throw new \Exception("Url not found");
	}

	public final function val($name) {
		if(array_key_exists($name, $this->_val)) {
			return $this->_val[$name];
		}
		// TODO define Exception.
		throw new \Exception("Val not found");
	}
	
	public final function dir($name) {
		if(array_key_exists($name, $this->_dir)) {
			return $this->_dir[$name];
		}
		//TODO Define Exception.
		throw new \Exception("Dir not found");
	}

}

?>

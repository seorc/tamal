<?php

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

require_once(TAMAL."/core/ContextAccessor.php");

class KeyException extends Exception {}

/**
 * Abstract class for all data managers
 */
//TODO: determine if it must be abstract
abstract class DataManager implements Serializable {
	protected $store;
	//TODO: a $dmStore could be used to keep DataManager instances
	//TODO: a $objectStore could be used to keep Object instances

	public function __construct() {
		$this->store = array();
	}

	public function serialize() {
		return serialize($this->store);
	}

	public function unserialize($serialized) {
		$this->store = unserialize($serialized);
	}

	public final function get($key) {
		if(array_key_exists($key, $this->store)) {
			return $this->store[$key];
		}
		//TODO: define EXC
		throw new KeyException("The key doesn't exist: $key");
	}

	public final function set($key, $val) {
		if(array_key_exists($key, $this->store)) {
			$this->store[$key] = $val;
		}
		//TODO: to define EXC
		throw new KeyException("The key doesn't exist: $key");
	}

	public final function drop($key) {
		if(array_key_exists($key, $this->store)) {
			unset($this->store[$key]);
		}
		//TODO: to define EXC
		throw new KeyException("The key doesn't exist: $key");
	}

	public final function push($key, $val) {
		if(array_key_exists($key, $this->store)) {
			//TODO: to define EXC
			throw new KeyException("The key already exists: $key");
		}
		$this->store[$key] = $val;
	}
}

?>

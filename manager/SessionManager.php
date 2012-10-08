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

require_once(TAMAL."/manager/DataManager.php");
require_once(TAMAL."/auth/User.php");

class SessionManager extends DataManager {
	
	const CLASS_KEY = "__SESSIONMANAGER__";
	const USER = "__USER__";

	static final public function load() {
		session_start();
		if(array_key_exists(self::CLASS_KEY, $_SESSION)) {
			return unserialize($_SESSION[self::CLASS_KEY]);
		}
		else {
			return new SessionManager();
		}
	}

	public function __construct() {
		parent::__construct();
		$this->push(self::USER, new User());
	}

	public function destroy() {
		$_SESSION = array();
		session_destroy();
		unset($this);
	}

}

?>

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

require_once(TAMAL."/manager/SessionManager.php");
require_once(TAMAL."/core/Config.php");

class Context {
	
	protected $session = null;
	protected $config = null;

	public function __construct(Config $c) {
		$this->config = $c;
		$this->loadManagers();
	}

	public function __destruct() {
		//TODO on destruct, all the manager must be saved to
		//backend session storage (like $_SESSION or a DB)
		$_SESSION[\tamal\manager\SessionManager::CLASS_KEY] =
		   	serialize($this->session);
	}

	protected function loadManagers() {
		$this->session = \tamal\manager\SessionManager::load();
	}

	// TODO must be final
	public function getDb($name) {
		// TODO implement
		return NULL;
	}

	public final function getUser() {
		return $this->session->get(\tamal\manager\SessionManager::USER);
	}

	public final function getConfig() {
		return $this->config;
	}

	public final function getSession() {
		return $this->session;
	}

}

?>

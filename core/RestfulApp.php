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

require_once(TAMAL."/core/App.php");

abstract class RestfulApp extends App {

	public function restful(\tamal\web\Request $r) {

		switch($r->getMethod()) {
		case 'GET':
			return $this->read($r);
		case 'POST':
			return $this->create($r);
		case 'PUT':
			return $this->update($r);
		case 'DELETE':
			return $this->delete($r);
		}
	}

	abstract protected function read(\tamal\web\Request $r); 
	abstract protected function create(\tamal\web\Request $r);
	abstract protected function update(\tamal\web\Request $r);
	abstract protected function delete(\tamal\web\Request $r);
}

?>

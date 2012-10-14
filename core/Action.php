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

class Action {
	
	protected $rc = NULL;
	protected $rm = NULL;
	protected $context = NULL;

	public function __construct(Context $context, $class, $method) {
		$this->context = $context;
		if(!class_exists($class)) {
			// TODO Define exception.
			throw new \Exception(
				"The action class does not exist: ".$class);
		}
		$this->rc = new \ReflectionClass($class);
		if(!$this->rc->hasMethod($method)) {
			// TODO Define exception.
			throw new \Exception("The action method does not exist: ".$method);
		}
		elseif(!$this->rc->getMethod($method)->isPublic()) {
			// TODO Define exception.
			throw new \Exception(
				"The action method cannot be run: ".$method);
		}
		$this->rm = $this->rc->getMethod($method);
	}

	public function run(\tamal\web\HttpRequest $request) {
		# Create an instance of the App.
		$classInstance = $this->rc->newInstance($this->context);
		# Execute the corresponding method of the instance created.
		return $this->rm->invoke($classInstance, $request);
	}

}

?>

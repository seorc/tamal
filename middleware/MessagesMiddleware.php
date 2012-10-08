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

require_once(TAMAL."/middleware/Middleware.php");

class MessageMiddleware extends Middleware {
	const MWKEY = "messagemiddleware";
	const WRAPPER_KEY = "messages";
	
	protected $messages;

	public function __construct() {
		parent::__construct();
		$this->messages = array();
	}

	public function addMessage($name, array $params) {
		$this->messages[] = array("msg" => $name, "params" => $params);
	}

	public function runRes(Response $res) {
		self::appendToWrap($res, self::WRAPPER_KEY, $this->messages);
	}

	/**
	 * Alias to {@link addMessage()}
	 **/
	public function add($name, array $params) {
		$this->addMessage($name, $params);
	}

}

?>

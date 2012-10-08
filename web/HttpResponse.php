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

require_once(TAMAL."/web/Response.php");
require_once(TAMAL."/web/HttpStatus.php");

class HttpResponse extends Response {
	
	protected $headers;
	protected $status;

	public function __construct($content = "", $status = HttpStatus::OK) {
		$this->status = $status;

		parent::__construct($content);
	}

	public function deploy() {
		$deployable = array(
			HttpStatus::OK,
			HttpStatus::CREATED,
			HttpStatus::ACCEPTED,
			HttpStatus::NOT_FOUND
		);

		$this->deployStatus();

		if(in_array($this->status, $deployable)) {
			parent::deploy();
		}
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	protected function deployStatus() {

		$msg = "";

		switch($this->status) {
		case HttpStatus::OK:
			$msg = "OK";
			break;
		case HttpStatus::CREATED:
			$msg = "Created";
			break;
		case HttpStatus::ACCEPTED:
			$msg = "Accepted";
			break;
		case HttpStatus::BAD_REQUEST:
			$msg = "Bad request";
			break;
		case HttpStatus::UNAUTHORIZED:
			$msg = "Unauthorized";
			break;
		case HttpStatus::FORBIDDEN:
			$msg = "Forbidden";
			break;
		case HttpStatus::NOT_FOUND:
			$msg = "Not found";
			break;
		}

		header("HTTP/1.1 {$this->status} $msg");
	}

	public function setHeader($header) {
		header($header);
	}

}

?>

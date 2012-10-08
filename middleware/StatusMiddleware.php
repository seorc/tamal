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

require_once(TAMAL."/core/Bootstrap.php");

/**
 * Defines a subprotocol of server-side processing status announcing
 * 
 * This middleware appends a status key to the middleware array wrapper
 * created by the Bootstrap object
 **/
class StatusMiddleware extends Middleware {
	const MWKEY = "statusmiddleware";
	const WRAPPER_KEY = "status";

	const ST_OK = 0;

	const ST_NOTLOGGEDIN = 10;
	const ST_NOPERMISSION = 11;
	const ST_LOCKED = 12;

	const ST_ERROR = 50;
	const ST_SYSERROR = 51;
	const ST_IOERROR = 52;
	const ST_DBERROR = 53;
	const ST_CALLERROR = 54;
	const ST_TIMEOUTERROR = 55;

	protected $status = self::ST_OK;

	public function runRes(Response $res) {
		self::appendToWrap($res, self::WRAPPER_KEY, $this->status);
		// XXX just for testing
		self::appendToWrap($res, "success", $this->status == self::ST_OK);
	}

	public function setStatus() {
		
	}
}

?>

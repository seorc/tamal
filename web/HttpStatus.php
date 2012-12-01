<?php
namespace tamal\web;

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

interface HttpStatus {

	const OK = 200;
	const CREATED = 201;
	const ACCEPTED = 202;
	const BAD_REQUEST = 400;
	const UNAUTHORIZED = 401;
	const FORBIDDEN = 403;
	const NOT_FOUND = 404;
	const REQUEST_TIMEOUT = 408;
	const CONFLICT = 409;
	const INTERNAL_SERVER_ERROR = 500;
}

?>

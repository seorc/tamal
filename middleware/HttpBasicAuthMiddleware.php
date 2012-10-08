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


class HttpBasicAuthMiddleware extends Middleware {

	const MWKEY = "httpbasicauthmiddleware";

	// XXX not required
	const WRAPPER_KEY = "httpbasicauth";

	protected $authenticator;

	protected $user;

	public function __construct() {
		$this->authenticator = NULL;

		$this->user = new User();
	}

	public function runReq(Request $req) {
		try {

			if($req->getAuthType() != HttpRequest::AUTH_BASIC) return;

			$this->user->authenticate(
				$req->getAuthUser(),
				$req->getAuthPwd());
		}
		catch(NotSetException $e) {
			throw new MiddlewareException(
				self::MWKEY
				.": You must define a UserAuthenticator for this middleware");
		}
	}

	public function setAuthenticator(UserAuthenticator $uauth) {
		$this->user->setAuthenticator($uauth);
	}

	public function validate() {
		if(!$this->user->isAuthenticated()) {
			throw new NotAuthenticated("The user could not be authenticated");
		}
	}
}

?>

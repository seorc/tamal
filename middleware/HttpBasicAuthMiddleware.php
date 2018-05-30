<?php

namespace tamal\middleware;

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

require_once TAMAL.'/middleware/Middleware.php';
require_once TAMAL.'/auth/User.php';

class HttpBasicAuthMiddleware extends Middleware {
    public const MWKEY = 'httpbasicauthmiddleware';

    // XXX not required
    public const WRAPPER_KEY = 'httpbasicauth';

    protected $authenticator;

    protected $user;

    public function __construct() {
        $this->authenticator = null;

        $this->user = new \tamal\auth\User();
    }

    public function runReq(\tamal\web\Request $req): void {
        try {
            if ($req->getAuthType() != \tamal\web\HttpRequest::AUTH_BASIC) {
                return;
            }

            $this->user->authenticate(
                $req->getAuthUser(),
                $req->getAuthPwd());
        } catch (NotSetException $e) {
            throw new MiddlewareException(
                self::MWKEY
                .': You must define a UserAuthenticator for this middleware');
        }
    }

    public function setAuthenticator(\tamal\auth\UserAuthenticator $uauth): void {
        $this->user->setAuthenticator($uauth);
    }

    public function validate(): void {
        if (!$this->user->isAuthenticated()) {
            throw new \tamal\auth\NotAuthenticated('The user could not be authenticated');
        }
    }
}

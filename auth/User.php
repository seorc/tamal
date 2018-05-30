<?php

namespace tamal\auth;

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

class NotSetException extends \Exception {
}
class NotAuthenticated extends \Exception {
}

class User {
    protected $loggedIn;
    protected $name;
    protected $authenticated;
    protected $userName;
    protected $password;
    protected $context;
    protected $authenticator;

    public function __construct() {
        $this->reset();
    }

    final public function reset(): void {
        $this->loggedIn = false;
        $this->authenticated = false;
    }

    final public function authenticate($userName, $password) {
        $this->hasAuthenticator();
        if ($this->authenticator->doAuthenticate($userName, $password)) {
            $this->userName = $userName;
            $this->password = \sha1($password);
            $this->authenticated = true;

            return true;
        }

        return false;
    }

    final public function login(): void {
        $this->hasAuthenticator();
        if ($this->authenticated && $this->authenticator->doLogin()) {
            $this->loggedIn = true;
        }
    }

    final public function logout() {
        $this->hasAuthenticator();
        if ($this->authenticator->doLogout()) {
            $this->reset();

            return true;
        }
    }

    final public function loggedIn() {
        return $this->loggedIn;
    }

    final public function setAuthenticator(UserAuthenticator $ua): void {
        $this->authenticator = $ua;
    }

    public function isAuthenticated() {
        return $this->authenticated;
    }

    public function getName() {
        return $this->userName;
    }

    final protected function hasAuthenticator(): void {
        if (null === $this->authenticator) {
            throw new NotSetException(
                'The UserAuthenticator has not been set');
        }
    }
}

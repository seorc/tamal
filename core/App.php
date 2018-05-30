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

require_once TAMAL.'/core/Context.php';

// XXX Shouldn't this exception go under \tamal\auth?
class NotLoggedInException extends \Exception {
}

class App extends ContextAccessor {
    public const NONE = 0;

    public const LOGIN = 1;
    public const PERMISSION = 2;
    public const MIDDLEWARE_VALIDATION = 4;

    protected function getDb($name) {
        return $this->context->getDb($name);
    }

    protected function getConfig() {
        return $this->context->getConfig();
    }

    protected function requires($what, array $perms = []): void {
        if ($what & self::LOGIN) {
            if (!$this->context->getUser()->loggedIn()) {
                throw new NotLoggedInException('The user has not logged in');
            }
        }
        if ($what & self::PERMISSION) {
            if (!$this->context->getUser()->loggedIn()) {
                throw new NotLoggedInException('The user has not logged in');
            }
        }
        if ($what & self::MIDDLEWARE_VALIDATION) {
            $this->context->runMiddlewareValidation();
        }
    }
}

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

class MiddlewareException extends \Exception {
}

abstract class Middleware {
    public const MWKEY = '_basemiddlewareclass_';

    public const RESPONSE_WRAPPER_KEY = 'data';

    public function __construct() {
    }

    public function runReq(\tamal\web\Request $req): void {
    }

    public function runRes(\tamal\web\Response $res): void {
    }

    public static function wrapResponse(\tamal\web\Response $r): void {
        $w = $r->getContent();
        $w = [
            self::RESPONSE_WRAPPER_KEY => $r->getContent(),
        ];
        $r->setContent($w);
    }

    public static function unwrapResponse(\tamal\web\Response $r): void {
        if (self::isWrapped($r)) {
            $w = $r->getContent();
            $c = $w[self::RESPONSE_WRAPPER_KEY];
            $r->setContent($c);
        } else {
            throw new MiddlewareException(
                'The Response object is not wrapped');
        }
    }

    public static function isWrapped(\tamal\web\Response $r) {
        $w = $r->getContent();

        return \is_array($w)
            && \array_key_exists(self::RESPONSE_WRAPPER_KEY, $w);
    }

    public static function appendToWrap(\tamal\web\Response $r, $wkey, $contents): void {
        if (!self::isWrapped($r)) {
            self::wrapResponse($r);
        }
        $c = $r->getContent();
        $c[$wkey] = $contents;
        $r->setContent($c);
    }

    public function validate(): void {
    }
}

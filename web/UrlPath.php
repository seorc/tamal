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

class NoNamedPathSegmentFound extends \Exception {
}

class UrlPath {
    public const DEFAULT_REGEXP = "/([a-zA-Z0-9_-].*\/)+/";

    public const NONE = '';
    public const CHOP_PATH = 'chop';
    public const SLASH = '/';

    protected $regexp;
    protected $pathSegments;

    public function __construct() {
        $this->setPathMatcher();
    }

    /**
     * Define the regexp to match the paths against, when validation occurs.
     *
     * @param $regexp string The regexp to use as matcher
     */
    public function setPathMatcher($regexp = self::DEFAULT_REGEXP): void {
        $this->regexp = $regexp;
    }

    /**
     * Obtain a named path segment stored into this object.
     */
    public function get($key, $flag = self::NONE) {
        $exists = \array_key_exists($key, $this->pathSegments);

        if ($exists && $flag == self::CHOP_PATH) {
            $result = \explode(self::SLASH, $this->pathSegments[$key]);
            // XXX removes the first slash (what if there isn't one, uh?)
            \array_shift($result);

            return $result;
        }

        if ($exists) {
            return $this->pathSegments[$key];
        }

        throw new NoNamedPathSegmentFound(
            'The path segment you are looking for does not exist: '.$key);
    }

    public function has($key) {
        return \array_key_exists($key, $this->pathSegments);
    }

    /**
     * Disect a path and store its parts into this object.
     */
    public function parse($path) {
        // to save the results
        $path_matches = [];

        if (\preg_match($this->regexp, $path, $path_matches)) {
            $this->loadMatches($path_matches);

            return true;
        }

        return false;
    }

    /**
     * Store a set of matches into this instance.
     */
    protected function loadMatches(array $matches): void {
        $this->pathSegments = $matches;
    }
}

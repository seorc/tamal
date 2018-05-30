<?php

namespace tamal\db;

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

require_once TAMAL.'/db/PostgresLang.php';
require_once TAMAL.'/db/MysqlLang.php';

class Dbms {
    public const POSTGRES = 1;
    public const MYSQL = 2;

    public static function getLang($dbms) {
        switch ($dbms) {
        case self::POSTGRES:
            return new PostgresLang();
        case self::MYSQL:
            return new MysqlLang();
        default:
            // TODO Make specific Exception for this.
            throw new \Exception('Unknown Dbms identifier');
        }
    }
}

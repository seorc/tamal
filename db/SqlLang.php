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

class SqlLang {
	public $_select = "SELECT";
	public $_and = "AND";
	public $_or = "OR";
	public $_from = "FROM";
	public $_order = "ORDER BY";
	public $_group = "GROUP BY";
	public $_where = "WHERE";
	public $_limit = "LIMIT";
	public $_offset = "OFFSET";
	public $_in = "IN";
	public $_distinct = "DISTINCT";
	public $_distinct_on = "DISTINCT ON (%s)";
}

?>

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

require_once(TAMAL."/db/Dbms.php");

class QueryBuilder {

	const USE_AND = 1;
	const USE_OR = 2;
	
	protected $lang;
	protected $dbms;
	
	protected $select = "";
	protected $from = "";
	protected $where = "";
	protected $group = "";
	protected $order = "";
	protected $limit = "";
	protected $offset = "";

	protected $tables;

	protected $distinct = false;

	public function __construct($dbms = Dbms::POSTGRES) {
		$this->dbms = $dbms;
		$this->lang = Dbms::getLang($dbms);

		$this->reset();
	}

	public function reset() {
		$this->select = "";
		$this->from = "";
		$this->where = "";
		$this->group = "";
		$this->order = "";
		$this->limit = "";
		$this->offset = "";

		$this->tables = array();
	}

	public function getQuery() {
		$l = $this->lang;

		$disctint = $this->buildDistinct();

		$this->buildFrom();

		$qry =
			$l->_select." "
				.($disctint ? $disctint." " : "")
				.$this->select
			." ".$l->_from." ".$this->from
			." ".($this->where	? $l->_where." ".$this->where	: "")
			." ".($this->group	? $l->_group." ".$this->group	: "")
			." ".($this->order	? $l->_order." ".$this->order	: "")
			." ".($this->offset	? $l->_offset." ".$this->offset	: "")
			." ".($this->limit	? $l->_limit." ".$this->limit	: "");

		return $qry;
	}

	public function addColumns($columns) {
		$this->select .= ($this->select ? ", " : "").$columns;
	}

	public function appendWhere($condition, $operator = self::USE_AND) {
		$l = $this->lang;

		if($operator == self::USE_AND) $operator = $l->_and;
		elseif($operator == self::USE_OR) $operator = $l->_or;
		else {
			// TODO Create a specific Exception for this.
			throw new \Excetpion("Unknown operator identifier");
		}

		$this->where .=
			($this->where ? " $operator " : "")."($condition)";
	}

	public function setDistinct($columns = true) {
		if($columns === false) {
			$this->distinct = $columns;
		}
		elseif($columns === true) {
			$this->distinct = $columns;
		}
		elseif(is_string($columns)) {
			$this->distinct = $columns;
		}
		else {
			// TODO Create a specific Exception for this.
			throw new \Excetpion("Incorrect argument to setDistinct");
		}
	}

	protected function buildDistinct() {
		$d = "";
		$l = $this->lang;
		if($this->distinct === true) {
			$d = $l->_distinct;
		}
		elseif(is_string($this->distinct)) {
			$d = sprintf($l->_distinct_on, $this->distinct);
		}
		return $d;
	}

	protected function buildFrom() {
		$this->from = implode(", ", $this->tables);
	}

	public function addTables() {
		
		$argv = func_get_args();

		for($i = 0; $i < count($argv); $i++) {
			$table = $argv[$i];
			if(!in_array($table, $this->tables)) {
				array_push($this->tables, $table);
			}
		}
	}

	public function addSorting($sorting) {
		$this->order .= ($this->order ? ", " : "").$sorting;
	}

	public function addGrouping($grouping) {
		$this->group .= ($this->group ? ", " : "").$grouping;
	}

	public function setOffsetLimit($offset, $limit) {
		$this->offset = $offset;
		$this->limit = $limit;
	}
}

?>

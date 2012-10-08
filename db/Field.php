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

class InvalidValue extends Exception {}

abstract class Field {

	CONST TO_SQL = 1;
	
	protected $name;
	protected $value;
	protected $valueSet;
	protected $default = NULL;

	protected $allowNull = TRUE;

	abstract protected function toSql();

	/**
	 * Validate the value assigned to this field.
	 *
	 * If everything is OK in the value, must return exactly TRUE. Any
	 * other return value will be interpreted as not valid. If it returns
	 * a string, it will be used as the error messege passed to the
	 * InvalidValue exception thrown by Field::set().
	 */
	abstract protected function validate($value);

	public function __invoke($format = self::TO_SQL) {
		switch($format) {
		case self::TO_SQL:
			return $this->toSql();
		}
	}

	public function __toString() {
		return $this->value;
	}

	public function __construct($name, $value = NULL) {
		$this->valueSet = FALSE;
		$this->name = $name;
		$this->set($value);
	}

	public function getName() {
		return $this->name;
	}

	public function set($value) {
		$is_valid = $this->validate($value);

		if($is_valid === TRUE) {
			$this->value = $value;
			$this->valueSet = TRUE;
			return;
		}

		if(is_string($is_valid)) {
			$err_msg = $is_valid;
		}
		else {
			$err_msg = 
				"The value assigned to \"{$this->name}\" is not valid: "
				.$value;
		}

		throw new InvalidValue($err_msg);
	}

	public function get() {
		return $this->value;
	}

	/*public function isSet() {
		return $this->valueSet;
	}*/
}

?>

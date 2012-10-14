<?php
namespace tamal\io\file_wrappers;

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

require_once(TAMAL."/io/FileWrapper.php");

/**
 * Wraps a PHPExcel_Writer_IWriter implementation
 */
class PHPExcelIWriterWrapper extends \tamal\io\FileWrapper {
	protected $file;
	protected $fileName;
	
	public function __construct(\PHPEXcel_Writer_IWriter $file, $file_name) {
		$this->file = $file;
		$this->fileName = $file_name;
	}

	public function flush() {
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=\"{$this->fileName}\"");
		header("Cache-Control: max-age=0");
		$this->file->save("php://output");
		
	}
	
	public function __destruct() {
		//$this->file->getPHPExcel(disconnectWorksheets);
		//unset($this->file);
	}
}

?>

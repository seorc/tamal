<?php
namespace tamal\io;

/* This file is part of Tamal.
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

require_once TAMAL."/io/FileWrapper.php";

class FileNotFound extends \Exception {}

class GenericFileWrapper extends FileWrapper {

	protected $cType;
	protected $file;
	protected $fileName;

	public function __construct($file, $file_name, $ctype, $cdisip = 'attachment') {

		$this->file = $file;
		$this->fileName = $file_name;
		$this->cType = $ctype;
		$this->cDisp = $cdisip;
		
		$this->validateFileExists();
	}
	
	public function validateFileExists() {
		
		if(!file_exists($this->file)) {
			throw new FileNotFound(
				'The file was not found or it was not possible to read it: '
				.$this->file);
		}
	}

	public function flush() {

		if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) {
			// Cope with legacy IE problems.
			header("Cache-Control: ");
			header("Pragma: ");
		}
		else {
			header("Cache-Control: No-cache");
		}
		header("Content-Disposition: {$this->cDisp}; filename={$this->fileName}");
		header("Content-Type: $this->cType");
		header("Content-Description: File Transfer");
		header("Content-Length: ".filesize($this->file));
		header("Expires: 0");
		readfile($this->file);
	}
}

?>

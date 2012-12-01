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

require_once(TAMAL."/web/Request.php");
require_once(TAMAL."/web/UrlPath.php");
require_once(TAMAL."/web/ContentType.php");

class ParamNotFound extends \Exception {}

class HttpRequest extends Request {

	// TODO implement Basic authentication support
	// TODO implement Digest authentication support

	const AUTH_BASIC = "Basic";
	const AUTH_DIGEST = "Digest";

	// XXX DEPRECATED
	protected $_post = null;
	// XXX DEPRECATED
	protected $_get = null;

	protected $_server = null;

	// TODO check this is being fulfilled
	// the path of the request
	protected $path;

	// the request's method
	protected $method;

	// TODO define a default (probably text/html), ONLY IF IT MAKES SENSE
	// stores the content type specified in the request
	protected $ctype;

	// to store the request's entity-body params
	protected $params;

	// if the request's entity-body is encoded in a special notation, it will
	// be stored here once it has been decoded
	protected $data;

	protected $authType;

	protected $authUser;

	protected $authPwd;

	/**
	 * Package the request relevant elements into this object.
	 */
	public function __construct() {
		
		$this->_server = $_SERVER;

		$this->method = $_SERVER["REQUEST_METHOD"];

		if(array_key_exists("CONTENT_TYPE", $_SERVER)) {
			$this->ctype = $_SERVER["CONTENT_TYPE"];
		}

		if($this->ctype == ContentType::JSON) {
			$this->parsePhpInput();
		}
		else {
			$this->loadRequestParams();
		}

		$this->loadCredentials();
	}

	/**
	 * Load authentication credentials into this objectcts structure.
	 */
	protected function loadCredentials() {
		if(array_key_exists("PHP_AUTH_USER", $_SERVER)
				&& array_key_exists("PHP_AUTH_PW", $_SERVER)) {
			$this->authType = self::AUTH_BASIC;
			$this->authUser = $_SERVER["PHP_AUTH_USER"];
			$this->authPwd = $_SERVER["PHP_AUTH_PW"];
		}
		/*
		if(array_key_exists("AUTH_TYPE", $_SERVER)) {
			$this->authType = $_SERVER["AUTH_TYPE"];
			if($this->authType == self::AUTH_BASIC) {
				$this->authUser = $_SERVER["PHP_AUTH_USER"];
				$this->authPwd = $_SERVER["PHP_AUTH_PWD"];
			}
		}
		 */
	}

	public function getAuthType() {
		return $this->authType;
	}

	public function getAuthUser() {
		return $this->authUser;
	}

	public function getAuthPwd() {
		return $this->authPwd;
	}

	protected function parsePhpInput() {
		// TODO take into account the ctype to parse the data
		// TODO validate that php://input has valid data
		$this->data = json_decode(file_get_contents("php://input"));
	}

	protected function loadRequestParams() {
		// The base idea for this code was taken (first, copy-pasted) from
		// http://stackoverflow.com/questions/2081894/handling-put-delete-arguments-in-php/5932067#5932067
		switch($this->method) {
		case "PUT":
		case "DELETE":
			parse_str(file_get_contents("php://input"), $this->params);
			// TODO probably this is not necesary
			$GLOBALS["_{$this->method}"] = $this->params;
			// XXX is it ok that the url params are packaged too
			// TODO analyze carefully what is this doing; I don't like it
			$_REQUEST = $this->params + $_REQUEST;
			break;
		case "GET":
			$this->params = $_GET;
			$this->_get = $_GET;
			break;
		case "POST":
			$this->params = $_POST;
			$this->_post = $_POST;
			break;
		}
	}

	// TODO optional 2th param to tell if param must come from GET, POST...?
	public function getParam($key) {
		if(array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}
		throw new ParamNotFound("The param key does not exist: $key");
	}

	// TODO DEPRECATED probably will be removed
	public function post($key) {
		if(array_key_exists($key, $this->_post)) {
			return $this->_post[$key];
		}
		//TODO Define Exception.
		throw new \Exception("The POST key does not exist: $key");
	}

	// TODO DEPRECATED probably will be removed
	public function get($key) {
		if(array_key_exists($key, $this->_get)) {
			return $this->_get[$key];
		}
		//TODO Define Exception.
		throw new \Exception("The GET key does not exist: $key");
	}

	public function getUrl() {
		return $this->_server["REQUEST_URI"];
	}

	/**
	 * Define the UrlPath of this object.
	 */
	public function setPath(UrlPath $path) {
		$this->path = $path;
	}

	/**
	 * Obtain the UrlPath object stored in this request.
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Alias of UrlPath::get().
	 *
	 * The interface is the same.
	 */
	public function getPathValue($key, $flag = UrlPath::NONE) {
		return $this->path->get($key, $flag);
	}

	public function getMethod() {
		return $this->method;
	}

	public function getCtype() {
		return $this->ctype;
	}

	public function getData() {
		return $this->data;
	}

}

?>

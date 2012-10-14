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

require_once(TAMAL."/web/HttpRequest.php");
require_once(TAMAL."/web/HttpResponse.php");
require_once(TAMAL."/web/HttpStatus.php");
require_once(TAMAL."/core/Context.php");
require_once(TAMAL."/core/UrlMatcher.php");

use tamal\web as tweb;

class Bootstrap extends Context {
	
	public $actionIdentifier = NULL;
	public $systems = NULL;

	protected $action = NULL;
	protected $middleware = NULL;
	protected $middlewareMap = NULL;
	protected $request = NULL;

	public function __construct(
			Config $c, UrlMatcher $um) {

		// Exceptions are thrown on environment preparing.
		try {
			parent::__construct($c);

			// XXX is this pretty?
			$this->systems = $um->getApps();

			// TODO improve the HttpRequest packaging
			$this->request = new tweb\HttpRequest($_POST, $_GET, $_SERVER);
			$this->action = $um->matchAction($this, $this->request);
		}
		catch(\Exception $e) {
			$this->deployException($e);
		}
	}

	protected function deployException(\Exception $e) {
		$e_class = get_class($e);
		$not_error_e = array(
			'tamal\\auth\\NotLoggedInException',
			'tamal\\auth\\NotAuthenticated');
		if($this->config->debug && !in_array($e_class, $not_error_e)) {
			echo "<h2>Tamal says an exception occurred</h2>"
				."<div style=\""
				."background-color: #ffffcc;"
				."border: 1px solid #ffcc00;"
				."padding: 8px;\">"
				."<h4>Exception detail</h4>"
				."<pre>"
				.$e
				."</pre></div>";
			exit;
		}
		else {

			$headers = array();

			// TODO Send email notification if *debug* mode is disabled.
			switch(get_class($e)) {
			case "tamal\\NoMatchFoundException":
				$http_status = tweb\HttpStatus::NOT_FOUND;
				break;
			case "tamal\\auth\\NotLoggedInException":
			case "tamal\\auth\\NotAuthenticated":
				$http_status = tweb\HttpStatus::UNAUTHORIZED;
				// TODO This shouldn't be hardcoded.
				$headers[] = 'WWW-Authenticate: Basic realm="Tamal"';
				break;
			case "ParamNotFound":
				$http_status = tweb\HttpStatus::BAD_REQUEST;
				break;
			default:
				$http_status = tweb\HttpStatus::INTERNAL_SERVER_ERROR;
			}
			$r = new tweb\HttpResponse($e->getMessage(), $http_status);

			for($i = 0; $i < count($headers); $i++) {
				$r->setHeader($headers[$i]);
			}

			$r->deploy();
			exit;
		}
	}

	/**
	 * Finds a middleware loaded into this obejct by its MWKEY
	 *
	 * @return Middleware
	 */
	public function getMiddleware($mwKey) {
		if(!is_array($this->middlewareMap)) {
			$this->middlewareMap = array();
			for($i = 0; $i < count($this->middleware); $i++) {
				$cl = new ReflectionClass($this->middleware[$i]);
				$this->middlewareMap[$cl->getConstant("MWKEY")] = $i;
			}
		}
		if(array_key_exists($mwKey, $this->middlewareMap)) {
			return $this->middleware[$this->middlewareMap[$mwKey]];
		}
		// TODO Define exception properly.
		throw new \Exception("The Middleware key '{$mwKey}' was not found");
	}

	/**
	 * Alias of {@link getMiddleware()}
	 */
	public function mw($mwKey) {
		return $this->getMiddleware($mwKey);
	}

	protected function loadMiddleware() {
		$this->middleware = $this->config->middleware;
	}

	protected function runMiddlewareReq(\tamal\web\Request $req) {
		if(is_a($req, "RawReqRes") || count($this->middleware) < 1) {
			return;
		}
		for($i = 0; $i < count($this->middleware); $i++) {
			$this->middleware[$i]->runReq($req);
		}
	}

	protected function runMiddlewareRes(\tamal\web\Response $res) {
		if(is_a($res, "RawReqRes") || count($this->middleware) < 1) {
			return;
		}
		// XXX what to do with wrapping?
		// Wrapping may be issued automatically by any middleware, but
		// I think it would be nice to make it explicit. The next
		// instruction can be omited anyway.
		//Middleware::wrapResponse($res);
		for($i = count($this->middleware) - 1; $i >= 0; $i--) {
			$this->middleware[$i]->runRes($res);
		}
	}

	// TODO must it expect the request?
	public function runMiddlewareValidation() {
		for($i = 0; $i < count($this->middleware); $i++) {
			$this->middleware[$i]->validate();
		}
	}

	public function execute() {
		// exceptions here are thrown during execution
		try {
			//$req = new HttpRequest($_POST, $_GET, $_SERVER);

			//TODO decide wether middleware shuold be loaded here
			$this->loadMiddleware();
			$this->runMiddlewareReq($this->request);
			$res = $this->action->run($this->request);
			$this->runMiddlewareRes($res);
			$res->deploy();
		}
		catch(\Exception $e) {
			$this->deployException($e);
		}
	}

}

?>

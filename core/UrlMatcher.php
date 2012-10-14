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

require_once(TAMAL."/core/ActionResolver.php");
require_once(TAMAL."/web/UrlPath.php");

class NoMatchFoundException extends \Exception {}

class UrlMatcher {
	
	protected $urls = array();
	protected $apps = array();

	public function __construct($urls, $apps) {
		$this->urls = $urls;
		$this->apps = $apps;
	}

	public function matchAction(Context $c, \tamal\web\HttpRequest $r) {
		$url = $r->getUrl();

		// to match the path and manage its parts
		$path = new \tamal\web\UrlPath();

		$url = preg_split("/\?/", $url);
		$url = $url[0];

		$url_params = "";
		if(count($url) > 1) {
			$url_params = $url[1];
		}

		foreach($this->urls as $u) {
			// TODO do not use this hardcoded 0
			$path->setPathMatcher($u[0]);

			if($path->parse($url)) {
				$r->setPath($path);
				// TODO do not use this hardcoded 1
				return ActionResolver::find($c, $u[1]);
			}
		}

		throw new NoMatchFoundException("Nothing here: $url");
	}

	public function getApps() {
		return $this->apps;
	}
}

?>

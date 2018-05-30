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

require_once TAMAL.'/core/Context.php';
require_once TAMAL.'/core/App.php';
require_once TAMAL.'/core/Action.php';

class ActionResolver {
    public const APP = 'app';
    public const ACTION = 'action';

    public static function find(Context $context, $action_id) {
        // Obtain the app referencies from by $action_id.
        $app_act = self::splitActionName($action_id);

        // Verify if the app exists in the apps map.
        if (\array_key_exists($app_act[self::APP], $context->systems)) {
            // Get the path to the solicited class and require it.
            $path = $context->systems[$app_act[self::APP]];

            $class_name = self::getClassNameFromPath($path);

            // TODO Manage this inclusion possible related errors.
            require_once $path;

            // Create the action object and return it.
            $act = new Action(
                $context, $class_name, $app_act[self::ACTION]);

            return $act;
        }
        // TODO Define exception.
        throw new \Exception('The action was not fund');
    }

    public static function splitActionName($ai) {
        $xuri = \explode('.', $ai);
        if (\count($xuri) != 2) {
            // TODO Define exception.
            throw new \Exception('The action is malformed');
        }

        return [self::APP => $xuri[0], self::ACTION => $xuri[1]];
    }

    /**
     * Find the class name contained in a path.
     *
     * This function assumes the class name is the same as the file name.
     *
     * @param $path string The path to search the class in
     */
    public static function getClassNameFromPath($path) {
        // TODO add support for Windows paths

        // get parts of the path
        $path_s = \explode('/', $path);

        // obtain the file name, wich must be equal to the class name
        // with .php extension
        $file_name = $path_s[\count($path_s) - 1];

        $file_matches = [];

        // XXX this regexp also rules the appropriate names for classes
        $fname_regexp = "/(?P<name>[A-Za-z0-9_].*)\.(?P<extension>php)$/";

        // try to find the class name in the file name
        if (!\preg_match($fname_regexp, $file_name, $file_matches)) {
            // TODO Define exception.
            throw new \Exception('The class name could not be obtained');
        }

        return $file_matches['name'];
    }
}

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

require_once TAMAL.'/io/FileWrapper.php';

/**
 * Wraps a ZipArchive object.
 */
class ZipArchiveWrapper extends \tamal\io\FileWrapper {
    public $autoRemove = false;

    protected $file;
    protected $fileName;

    public function __construct(\ZipArchive $file, $file_name) {
        $this->file = $file;
        $this->fileName = $file_name;
    }

    public function __destruct() {
    }

    public function flush(): void {
        // TODO Determine whether it is necesary.
        if (\ini_get('zlib.output_compression')) {
            \ini_set('zlib.output_compression', 'Off');
        }

        $filename = $this->file->filename;
        $this->file->close();

        \header("Content-Disposition: attachment; filename={$this->fileName}");
        \header('Content-Type: application/zip');
        \header('Content-Description: File Transfer');
        \header('Content-Length: '.\filesize($filename));
        \header('Expires: 0');

        \readfile($filename);

        if ($this->autoRemove) {
            \unlink($filename);
        }
    }
}

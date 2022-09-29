<?php

function corgi_get_files($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path) && preg_match('/\.(js|php)$/', $path)) {
            $time = filemtime($path);
            $results[] = array(
                'time' => $time,
                'file' => $path
            );
        } else if ($value !== '.' && $value !== '..') {
            corgi_get_files($path, $results);
        }
    }

    return $results;
}
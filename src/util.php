<?php

/*
 * some functions that should be implemented in PHP
 */

/**
 * Concat a list of directories and filename
 * @param string one or many parameters of directory chunks to join
 * @return string
 * @see https://stackoverflow.com/a/15575293
 */
function join_paths(): string {
    $paths = array();

    foreach (func_get_args() as $arg) {
        if ($arg !== '') {
            $paths[] = $arg;
        }
    }

    return preg_replace('#/+#', '/', join('/', $paths));
}

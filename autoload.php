<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

spl_autoload_register(function ($class) {
    // NAMESPACE PERFIX
    $prefix = 'Black_Widgets\\';

    // base directory for the namespace prefix
    $base_dir = BLACK_WIDGETS_PLUGIN_PATH . '/includes/';

    // If the class use the namespace prefix
    $namespace_and_class = strlen($prefix);
    if (strncmp($prefix, $class, $namespace_and_class) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $namespace_and_class);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
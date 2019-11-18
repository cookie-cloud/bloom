<?php

spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'Bloom')) { // Autoload our packages only

        $base_dir = __DIR__ . '/src';

        $class = ltrim(str_replace('\\', '/', $class), 'Bloom');

        $file = $base_dir . $class . '.php';

        include_once $file;
    }
});
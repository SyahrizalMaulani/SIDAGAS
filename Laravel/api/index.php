<?php

$paths = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
];

foreach ($paths as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

require __DIR__ . '/../public/index.php';
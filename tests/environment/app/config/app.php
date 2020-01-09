<?php
/**
 * Application configuration file.
 */

return [
    'namespace' => 'MyApp',
    'type' => 'theme',
    'version' => '1.0.0',
    'paths' => [
        'base'          => __DIR__ . '/../',
        'controllers'   => __DIR__ . '/../Controllers/',
        'views'         => __DIR__ . '/../../assets/views/',
    ],
    'localize' => [
        'textdomain'    => 'my-app',
    ],
];
<?php

use DI\Container;
use cache\MovieCache;
use cache\PostCache;

// Include all PHP files in the repository directory
foreach (glob(__DIR__ . '/../src/cache/*.php') as $filename) {
    require_once $filename;
}

// Create a new DI container instance
//$app = new Container();

require  __DIR__."/db.php";

$app->set('cache.post', function (Container $container) {
    return new PostCache($container->get('database'));
});

$app->set('cache.movie', function (Container $container) {
    return new MovieCache($container->get('database'));
});


// Return the DI container
return $app;

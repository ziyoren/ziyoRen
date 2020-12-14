<?php

return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {

    // 应用的路由写在下面

    $route->addRoute('GET', '/', 'App\\Controller\\IndexController@index');

    $route->addRoute('GET', '/mysql', 'App\\Controller\\Test@mysql');

    // {id} must be a number (\d+)
    $route->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    $route->addRoute('GET', '/mytest/{id:\d+}', 'App\\Controller\\Test::ljs');

    // The /{title} suffix is optional
    $route->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');

});


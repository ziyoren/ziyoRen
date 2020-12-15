<?php

return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {

    // 应用的路由写在下面

    $route->addRoute('GET', '/', 'App\\Controller\\IndexController@index');

});


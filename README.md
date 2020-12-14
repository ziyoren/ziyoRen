```shell
          ____  _           ___  _____  __
         /_  / (_)_ _____  / _ \/ __/ |/ /
          / /_/ / // / _ \/ , _/ _//    / 
         /___/_/\_, /\___/_/|_/___/_/|_/  
               /___/      
```

# ZiyoREN

一个基于Swoole扩展，简单、轻量且高性能的PHP协程框架。

## 功能特色
* HTTP Server
* TCP Server
* WebSocket Server
* PDO连接池
* Redis连接池

## 安装
```shell
composer require ziyoren/
```

## 文档

### 路由
#### 路由一：以参数方式指定Controller和Action
参数方式直接指定Controller和Action，简洁明了，无需程序预处理路由信息，非常高效，我们推荐用这种方式。

1. get方式指定
> 示例：http://127.0.0.1:9566/?_c=Test&_a=index
> 
> _c: 指定Controller。_c=Test，表示调用App\Controller\Test类
> 
> _a: 指定Action。 _a=index，表示调用Controller类里的index方法

2. header方式指定

```js
//nodejs

var axios = require('axios');
var qs = require('qs');
var data = qs.stringify({
  'p3': 'v3',
  'p5': 'v5' 
});
var config = {
  method: 'post',
  url: 'http://127.0.0.1:9566/',
  headers: { 
    'Controller': 'Test',  //请求App\Controller\Test
    'Action': 'index'      //请求App\Controller\Test的index方法
  },
  data : data
};

axios(config)
.then(function (response) {
  console.log(JSON.stringify(response.data));
})
.catch(function (error) {
  console.log(error);
});
```


#### 路由二：[基于(nikic/FastRoute)](https://github.com/nikic/FastRoute)
```php
// /config/router.php

return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {

    // 应用的路由写在下面
    
    $route->addRoute('GET', '/', 'App\\Controller\\IndexController@index');

    // {id} must be a number (\d+)
    $route->addRoute('GET', '/user/{id:\d+}', 'App\\Controller\\Test::sunline');
    
    // The /{title} suffix is optional
    $route->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'App\\Controller\\Test::sunline');

});

```

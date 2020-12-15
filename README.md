```shell
          ____  _           ___  _____  __
         /_  / (_)_ _____  / _ \/ __/ |/ /
          / /_/ / // / _ \/ , _/ _//    / 
         /___/_/\_, /\___/_/|_/___/_/|_/  
               /___/      PHP Coroutine Framework
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
composer require ziyoren/ziyoren
```

## 文档

### 路由

[基于(nikic/FastRoute)](https://github.com/nikic/FastRoute)
```php
// 路由配置文件：/config/router.php

return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {

    // 应用的路由写在下面
    
    $route->addRoute('GET', '/', 'App\\Controller\\IndexController@index');

    // {id} must be a number (\d+)
    $route->addRoute(['GET', 'POST'], '/user/{id:\d+}', 'App\\Controller\\Test::sunline');
    
    // The /{title} suffix is optional
    $route->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'App\\Controller\\Test::sunline');

});

```

#### 基本路由

```php
$route->addRoute($method, $routePattern, $handler);
```

> **$method:** `String|Array` 支持GET, POST, PUT, PATCH, DELECT和HEAD这些有效的处理方法，可以使用数组指定多个有效方法。如：['GET', 'POST']
> 
> **$routePattern:** `String` Uri地址，支持正则变量定义。如：/user/{id:\d+}; 
> 
> **$handler:** `String` 指定执行的控制器与方法。格式：App\Controller\IndexController@index

#### 路由分组

```php
$route->addGroup('/admin', function (RouteCollector $r) {
    $r->addRoute('GET', '/do-something', 'handler');
    $r->addRoute('GET', '/do-another-thing', 'handler');
    $r->addRoute('GET', '/do-something-else', 'handler');
});
```
上面的定义等同下面的结果：
```php
$route->addRoute('GET', '/admin/do-something', 'handler');
$route->addRoute('GET', '/admin/do-another-thing', 'handler');
$route->addRoute('GET', '/admin/do-something-else', 'handler');
```

#### 路由参数

路由定义一个id参数

```php
$route->addRoute(['GET', 'POST'], '/user/{id:\d+}', 'App\\Controller\\Test::sunline');
```

控制器示例

```php
namespace App\Controller;

use ziyoren\Http\Controller;

class Test extends Controller
{
    
    public function sunline($id)
    {
        return ['id' => $id];
    }
}
```

> 请求： http://127.0.0.1:9567/user/123
> 
> 结果： {'id':'123'}

### 控制器

一个基础控制器类的例子。该类继承了 `ziyoren\Http\Controller` 基础控制器。
```php
namespace App\Controller;

use ziyoren\Http\Controller;  //框架基础控制器类
use App\Model\User;

class UserController extends Controller
{
    /**
     * 显示指定用户的简介
     *
     * @param  int $id
     * @return array
     */
    public function show($id)
    {
        return User::findOne($id);
    }

}
```

#### HTTP Request对象

`ziyoREN` 封装了一个继承了`Swoole\Http\Request`，且符合PSR7标准的Request对象。控制器内可以用 `$this->request()` 调用。[Swoole\Http\Request 文档](https://wiki.swoole.com/#/http_server?id=httprequest)
```php
class UserController extends Controller
{
    
    public function swoole()
    {
        return $this->request()->header; //返回Swoole\Http\Request->header
    }
    
    public function psr7()
    {
        return $this->request()->getHeaders(); //返回PSR7标准的Headers
    }

}
```

#### HTTP Response对象

`ziyoREN` 提供了一个Swoole原装的 `Swoole\Http\Response` 对象。暂未进行PSR7标准封装。控制器内可以用 `$this->response()` 调用。[详见Swoole文档: Http\Response](https://wiki.swoole.com/#/http_server?id=httpresponse)

```php
class UserController extends Controller
{
    
    public function swoole_response()
    {
        $this->response()->header('My-header', 'Custom header'); 
        return ['My', 'Custom', 'header'];
    }
    
}
```

> 未封装高级的视图模块，所以用来做API接口服务再适合不过 ^_^

### 数据库


`ziyoREN` 的数据库模型基于 `catfan/medoo` 扩展。同时支持 [Swoole的连接池。](https://wiki.swoole.com/#/coroutine/conn_pool) 实现了自动归还连接、事务等功能。

#### PHP_PDO 扩展列表
|Databases | Driver  |
| -------- | ------- |
|Mysql, MariaDB | php_pdo_mysql |
|MSSQL(Linux/UNIX) | php_pdo_dblib / php_pdo_sqlsrv |
|Oracle  |  php_pdo_oci |
|Oracle version 8 | php_pdo_oci8 |
|SQLite  | php_pdo_sqlite |
|PostgreSQL | php_pdo_pgsql |
|Sybase | php_pdo_dblib |

#### 配置
数据库的配置文件在 `config/databases.php` 文件中，你可以在这个文件中定义数据库的连接配置。
```php
<?php
return [

    // [必需的配置项]
    'database_type' => 'mysql',  //Driver 
    'database_name' => 'test',   //数据库名称
    'server' => 'localhost',     //服务器地址
    'username' => 'root',        //用户名
    'password' => '',            //数据库密码

    // [可选配置]
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'port' => 3306,

    // [可选配置] 表名前缀，默认为空
    'prefix' => '',

    // [可选配置] 是否启用日志
    'logging' => false,

    // [可选配置] MySQL socket (shouldn't be used with server and port)
    'socket' => null,  // '/tmp/mysql.sock',

    // [可选配置] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    // 'option' => [
    //     PDO::ATTR_CASE => PDO::CASE_NATURAL
    // ],
    'option' => [],

    // [可选配置] Medoo will execute those commands after connected to the database for initialization
    // 'command' => [
    //     'SET SQL_MODE=ANSI_QUOTES'
    // ],
    'command' => [],

    // [可选配置] 连接池数量
    'size' => 64,
];
```

#### 模型定义

模型定义在项目的app/Model目录里，需要从 `ziyoren\Database\Model` 继承。
```php
<?php
namespace App\Model;

use ziyoren\Database\Model;

class Users extends Model
{
    protected $tableName = 'users';  //不含前缀的表名, 如prefix配置为sl_，即表名为sl_users。指定$realTableName时无效

    protected $realTableName = 'sl_users'; //指定完整的表名，将忽略数据库配置中的前缀(prefix)

}
```


#### 数据库查询

```php
class Users extends Model
{

    protected $realTableName = 'sl_users';

    public function find()
    {
        $field = ['u_id', 'u_name', 'u_sex'];
        $where = ['u_email' => 'foo@bar.com'];
        return $this->select($field, $where); 
        // select `u_id`, `u_name`, `u_sex` from `sl_users` where u_email = 'foo@bar.com'
    }
}
```

从以上示例可以看到，基础模型的select方法跟 `Medoo` 原生的语法相比，少一个表名参数，其他的参数结构与 `Medoo` 一致。

**方法列表**

| ziyoREN Model | Medoo |
| ------------- | ----- |
| **Query** |
| select($columns, $where) | select($table, $columns, $where) |
| insert($data) |  insert($table, $data) |
| update($data, $where) | update($table, $data, $where) |
| delete($where) |  delete($table, $where) |
| replace($columns, $where) | replace($table, $columns, $where) |
| get($columns, $where) | get($table, $columns, $where) |
| has($where) | has($table, $where) |
| rand($column, $where) | rand($table, $column, $where) |
| **Aggregation** | 
| count($where) | count($table, $where) |
| max($column, $where) | max($table, $column, $where)  |
| min($column, $where) | min($table, $column, $where)  |
| avg($column, $where) | avg($table, $column, $where) |
| sum($column, $where) | sum($table, $column, $where) |
| **Management** |
| create($columns, $options) | create($table, $columns, $options) |
| drop() |  drop($table) |

**一致的方法列表**

| ziyoREN Model & Medoo | 示例 |
| ------------- | ---- |
| id() | `$db->id(); ` //返回最后插入记录的ID |
| query(string $query) | `$db->query('SELECT email FROM account')->fetchAll();` |
| quote(string $string) | `$data = "Medoo"; echo "We love " . $db->quote($data); // We love 'Medoo' `
 |
| error() | `var_dump( $db->error() );` |
| log() | `var_dump( $db->log() );` |
| last() | `var_dump( $db->last() );` |

**事务方法**

| ziyoREN Model | 说明 |
| ------------- | --- |
| beginTransaction() | 开启事务 `$db->beginTransaction()` |
| commit() | 提交事务 `$db->commit()` |
| rollback() | 回滚事务 `$db->rollback()` |

> Medoo的事务方法 `action($callback)` 也是可用的。


同时，基础模型中返回一个 `Medoo` 对象，可用于兼容`Medoo`的原生用法。在模型中用 `$this->db()` 就可以获取。

```php
class Users extends Model
{

    public function medoo()
    {
        $field = ['u_id', 'u_name', 'u_sex'];
        $where = ['u_email' => 'foo@bar.com'];
        return $this->db()->select('users', $field, $where); 
        // select `u_id`, `u_name`, `u_sex` from `sl_users` where u_email = 'foo@bar.com'
    }
}
```

### Redis

使用了由 [Swoole](https://wiki.swoole.com/#/coroutine/conn_pool) 提供的Redis连接池+phpredis。实现了自动归还连接功能。

#### 配置

Redis的配置文件在 `config/redis.php` 文件中。

```php
<?php

return [
    'host' => '127.0.0.1',  // Redis服务器地址
    'port' => 6379,         // Redis服务器端口
    'timeout' => 0.0,       // 
    'auth' => '',           // 密码
    'db_index' => 0,        // 数据库
    'size' => 64,           // 连接池数量
];
```

#### 使用

```php
$redis = new BaseRedis();
$key = 'test:BaseRedis';
$redis->set($key, 'ziyoREN-'. time());
$rst = $redis->get($key);
```

### 配置文件

#### 项目配置

项目配置文件在 `config/config.php`

```php
return [
    'app_name' => 'ziyoren',          // 项目名称
    'timezone' => 'Asia/Shanghai',    // 时区
];
```

#### HTTP服务配置

HTTP服务配置文件在 `config/http-server.php`

```php
return [
    'host' => '0.0.0.0',
    'port' => 9567,
];
```

# prophiler-psr7-middleware
This package provides a slim PSR-7 middleware implementation based on the zendframework/zend-stratigility package. The
middleware is responsible for "adding" the Prophiler Toolbar output to the Response object.

[![Build Status](https://travis-ci.org/bitExpert/prophiler-psr7-middleware.svg?branch=master)](https://travis-ci.org/bitExpert/prophiler-psr7-middleware)
[![Dependency Status](https://www.versioneye.com/user/projects/5750a1a191bfda0044c0e84c/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5750a1a191bfda0044c0e84c)

Installation
------------

The preferred way of installing `bitexpert/prophiler-psr7-middleware` is through Composer. Simply add 
`bitexpert/prophiler-psr7-middleware` as a dependency:

```
composer.phar require bitexpert/prophiler-psr7-middleware
```

How to use it
-------------

Create Prophiler toolbar:

```php
    $prophiler = new \Fabfuel\Prophiler\Profiler();
    $toolbar = new \Fabfuel\Prophiler\Toolbar($prophiler);
```

Set-up your PSR-7 middleware, e.g. by using zendframework/zend-stratigility:

```php
    $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
    $response = \Zend\Diactoros\Response();

    $app = new \Zend\Stratigility\MiddlewarePipe();
```

Add the ProphilerMiddleware to the Middleware pipe:

```php
    $app->pipe(new \bitExpert\Http\Middleware\Psr7\Prophiler\ProphilerMiddleware($toolbar));
```
"Execute" the Middleware pipe:

```php
    $response = $app($request, $response);
```
To use the ProphilerMiddleware in a [Zend Expressive](https://github.com/zendframework/zend-expressive) application 
follow [this guide](https://blog.bitexpert.de/blog/using-prophiler-with-zend-expressive/).

License
-------

The Prophiler PSR7 Middleware is released under the Apache 2.0 license.

# prophiler-psr7-middleware
This package provides a slim PSR-7 middleware implementation based on the zendframework/zend-stratigility package. The
middleware is responsible for "adding" the Prophiler Toolbar output to the Response object.

[![Build Status](https://travis-ci.org/bitExpert/prophiler-psr7-middleware.svg?branch=master)](https://travis-ci.org/bitExpert/prophiler-psr7-middleware)
[![Coverage Status](https://coveralls.io/repos/github/bitExpert/prophiler-psr7-middleware/badge.svg?branch=master)](https://coveralls.io/github/bitExpert/prophiler-psr7-middleware?branch=master)

## Installation

The preferred way of installing `bitexpert/prophiler-psr7-middleware` is through Composer. Simply add 
`bitexpert/prophiler-psr7-middleware` as a dependency:

```
composer.phar require bitexpert/prophiler-psr7-middleware
```

## How to use the Prophiler PSR7 Middleware

Create the Prophiler toolbar:

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

## How to add the Prophiler PSR7 Middleware to an Expressive app

### Expressive 1.x

Register a pre_routing middleware in `config/autoload/middleware-pipeline.local.php`:

```php
return [
    'middleware_pipeline' => [[
        'middleware' => bitExpert\Http\Middleware\Psr7\
            Prophiler\ProphilerMiddleware::class,
        'priority' => 11000,
    ]]
];
```

Add a factory definition to `config/autoload/dependencies.global.php`:

```php
return [
    'dependencies' => [
        'factories' => [
            bitExpert\Http\Middleware\Psr7\Prophiler\
                ProphilerMiddleware::class =>
                App\Middleware\ProphilerFactory::class
        ]
    ]
];
```

The `\App\Middleware\ProphilerFactory` implementation looks like this:

```php
namespace App\Middleware;

use Interop\Container\ContainerInterface;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Toolbar;
use bitExpert\Http\Middleware\Psr7\Prophiler\ProphilerMiddleware;

class ProphilerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $prophiler = new Profiler();
        $toolbar = new Toolbar($prophiler);
        return new ProphilerMiddleware($toolbar);
    }
}
```

### Expressive 2.x (Programmatic Pipelines)

Adding the following code snippet to the `config/pipeline.php` file:

```php
$app->pipe(ErrorHandler::class);

$debug = $app->getContainer()->get('config')['debug'] ?? false;
if ($debug) {
   $prophiler  = new \Fabfuel\Prophiler\Profiler();
   $toolbar    = new \Fabfuel\Prophiler\Toolbar($prophiler);
   $middleware = new \bitExpert\Http\Middleware\Psr7\Prophiler\ProphilerMiddleware($toolbar);
   $app->pipe($middleware);
}

$app->pipe(ServerUrlMiddleware::class);
```

## Configure Prophiler

By default Prophiler will not log or profile anything. You can add custom benchmarks by adding
the following code snippet to the code you want to profile:

```php
$profiler->start('\My\Class::doSomeOtherThing', ['additional' => 'information'], 'My Component');

// here be your custom code

$profiler->stop();
```

In addition to that Prophiler offers a lot of [adapters and decorators](https://github.com/fabfuel/prophiler#adapters-and-decorators) 
for 3rd party tools and libraries.

## License

The Prophiler PSR7 Middleware is released under the Apache 2.0 license.

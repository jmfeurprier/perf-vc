VC
==

VC (View, Controller) is a simple and lightweight PHP View Controller stack.

## Installation & Requirements

Install it with [Composer](https://getcomposer.org/):

```shell script
composer require perf/vc
```

## Usage

### Initializing the front controller

```php
<?php

use perf\Vc\FrontController;

$routesPath    = __DIR__ . '/routes.xml';
$viewsBasePath = __DIR__ . '/view/';

$frontController = FrontController::createBuilder()
	->setViewsBasePath($viewsBasePath)
	->setRoutesPath($routesPath)
	->build()
;
```

### Running the front controller

```php
<?php

use perf\Vc\RequestPopulator;

// Create and populate a HTTP request with values from super-globals ($_GET, $_POST, $_SERVER, etc).
$requestPopulator = new RequestPopulator();

$request = $requestPopulator->populate();

// Route, execute, and retrieve result into a HTTP response.
$response = $frontController->run($request);

// Render HTTP response (headers and content) to user.
$response->send();
```

Or, shorter:

```php
<?php

$requestPopulator = new RequestPopulator();

$frontController->run($requestPopulator->populate())->send();
```

VC
==

VC (View, Controller) is a simple and lightweight PHP View Controller stack.

## Installation & Requirements

VC has no dependencies on other libraries.

Install it with [Composer](http://getcomposer.org/):

```json
{
	"require":
	{
		"perf/vc"  : "~1.0"
	}
}
```

## Usage

### Initializing the front controller

```php
<?php

$routesPath    = __DIR__ . '/routes.xml';
$viewsBasePath = __DIR__ . '/view/';

$frontController = \perf\Vc\FrontController::createBuilder()
	->setViewsBasePath($viewsBasePath)
	->setRoutesPath($routesPath)
	->build()
;
```

### Running the front controller

```php
<?php

// Create and populate a HTTP request with values from super-globals ($_GET, $_POST, $_SERVER, etc).
$requestPopulator = new \perf\Vc\RequestPopulator();
$request = $requestPopulator->populate();

// Route, execute, and retrieve result into a HTTP response.
$response = $frontController->run($request);

// Render HTTP response (headers and content) to user.
$response->send();
```

Or, shorter:

```php
<?php

$requestPopulator = new \perf\Vc\RequestPopulator();
$frontController->run($requestPopulator->populate())->send();
```

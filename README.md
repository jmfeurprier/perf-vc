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
		"perf/vc" : "~2.0"
	}
}
```

## Usage

### Initializing the front controller

```php
<?php

// Path where routing file can be found.
$routesPath = __DIR__ . '/routes.xml';

// Base path for view files (which must follow this structure: {views-base-path}/{module}/{action}.php)
$viewsBasePath = __DIR__ . '/view/';

$frontController = new \perf\Vc\FrontController(
	$container,
	$controllerFactory,
	$router,
	$responseBuilderFactory,
	$redirectionHeadersGenerator
);
```

### Running the front controller

```php
<?php

// Create and populate a HTTP request with values from super-globals ($_GET, $_POST, $_SERVER, etc).
$request = \perf\Vc\Request::createPopulated();

// Route, execute, and retrieve result into a HTTP response.
$response = $frontController->run($request);

// Render HTTP response (headers and content) to user.
$response->send();
```

Or, shorter:

```php
<?php

$frontController->autoHandle();
```

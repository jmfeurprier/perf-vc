VC
==

VC (View, Controller) is a simple and lightweight PHP View Controller stack.

## Installation & Requirements

Install it with [Composer](http://getcomposer.org/):

```shell script
composer require perf/vc
```

## Usage

```php
<?php

use perf\Vc\VcRunner;

$container = ...; // retrieve Dependency injection container from your application.

$runner = $container->get(VcRunner::class);
$runner->run();
```

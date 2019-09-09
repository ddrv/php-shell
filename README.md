# ddrv/shell

# Install

```text
composer require ddrv/shell
```

# Usage

## Local

```php
<?php

$shell = new Ddrv\Shell\LocalShell('/tmp', ['APP_ENV' => 'test', 'APP_SECRET' => '$3cr3t'], true);

/* or
$shell = new Ddrv\Shell\LocalShell();
$shell
    ->setCwd('/tmp')
    ->setEnv('APP_ENV', 'test')
    ->setEnv('APP_SECRET', '$3cr3t')
    ->mergeErrorsAndOutput();
*/

$result = $shell->exec('ls -l');

var_dump($result->getExitCode()); // 0
var_dump($result->getOutput()); // list directories in /tmp
var_dump($result->getErrors()); // null
```
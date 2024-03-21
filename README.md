# Deployer recipes

## Install

`composer require starring-jane/deployer-recipes`

## Archive recipe

```php
// deploy.php
<?php

namespace Deployer;

// Add the recipe
require __DIR__ . '/vendor/starring-jane/deployer-recipes/archive.php';

// Keep at least 2 releases to be able to archive the previous release
set('keep_releases', 2);

// Archive the previous release after the symlink update
after('deploy:symlink', 'archive:archive');

// Put the archived release back to rollback
before('rollback', 'archive:unarchive');

// Any other configuration you already had
...
```

## Diskspace recipe

This recipe checks if there is enough disk space available to create a release.
It checks the sizes of previous releases to calculate the required space.
If insufficient disk space is available it will throw an error and stop the deploy

```php
// deploy.php
<?php

namespace Deployer;

// Add the recipe
require __DIR__ . '/vendor/starring-jane/deployer-recipes/diskspace.php';

// Add the 'diskspace:check' task at the beginning of your deploy tasks
task('deploy', [
    'deploy:info',
    'deploy:setup',
    'diskspace:check', // NEW TASK
    'deploy:lock',
    'deploy:release',
    ...
]);

// Any other configuration you already had
...
```

## Contributors

* Maxim Vanhove (maxim@starringjane.com) [![Twitter Follow](https://img.shields.io/twitter/follow/MrMaximVanhove.svg?style=social&logo=twitter&label=Follow)](https://twitter.com/MrMaximVanhove)

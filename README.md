# Deployer recipes

## Install

`composer install starring-jane/deployer-recipes`

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

## Contributors

* Maxim Vanhove (maxim@starringjane.com) [![Twitter Follow](https://img.shields.io/twitter/follow/MrMaximVanhove.svg?style=social&logo=twitter&label=Follow)](https://twitter.com/MrMaximVanhove)

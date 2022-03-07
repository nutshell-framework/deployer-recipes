# Deployer recipes

The Deployer recipes used at [Erdmann & Freunde][1].

Intended to work with Contao version 4.13+ and Deployer 7. An opinionated extension of the [default Contao recipe][2].

## Usage

[Install Deployer][3] globally or per project.

Create a _deploy.php_ inside the project. Either use single recipes or the simplified [_project.php_][4] recipe.

```php
<?php

// deploy.php

namespace Deployer;

import(__DIR__.'/vendor/nutshell-framework/deployer-recipes/recipe/project.php');
import('contrib/cachetool.php');

set('rsync_src', __DIR__);

host('www.example.org')
    ->set('remote_user', 'acme')
    ->set('http_user', 'acme')
    ->set('deploy_path', '/home/www/{{hostname}}')
    ->set('bin/php', 'php8.1')
    ->set('bin/composer', '{{bin/php}} /home/composer.phar')
    ->set('cachetool_args', '--web=SymfonyHttpClient --web-path=./{{public_path}} --web-url=https://{{hostname}}')
;

after('deploy:success', 'cachetool:clear:opcache');

// Project-specific exclude
add('exclude', [
    '.githooks',
]);

// Project-specific tasks
task('yarn:build', function () {
    runLocally('yarn run prod');
});

before('deploy', 'yarn:build');
```

### Deploy

- `dep deploy [host]`

### Files sync

- `dep files:retrieve [host]` syncs the remote _/files_ folder with the local _/files_ folder

### Database helpers

These tasks restore/release the database local <-> remote unidirectionally.

- `dep database:retrieve [host]` downloads a database dump from remote and overwrites the local database
- `dep database:release [host]` overwrites the remote database with the local one

## Other

### Upgrading from Deployer 6

First, it is easiest to create the `deploy.php` from scratch since it is much simplified now and the host config
changed.

Second, since the release history numbering is not compatible between v6 and v7, you need to specify the release name
manually for the first time. Otherwise, you start with release 1.

- Find out the latest release (SSH onto the remote machine, look into the _releases_ folder), e.g., "42".
- `dep deploy -o release_name=43`

Note: Old releases from Deployer 6 won't be cleaned up automatically. Delete the old release manually. (Actually, you
can also start with release 1, that's no big deal.)

----

[1]: https://erdmann-freunde.de/
[2]: https://docs.contao.org/manual/en/guides/deployer/
[3]: https://deployer.org/docs/7.x/installation
[4]: /recipe/project.php

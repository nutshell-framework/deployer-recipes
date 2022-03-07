<?php

namespace Deployer;

// Switch when <https://github.com/deployphp/deployer/pull/2851> is represented in a tagged version
import(__DIR__ . '/contao.php');
//import('recipe/contao.php');
import(__DIR__ . '/contao-rsync.php');
import(__DIR__ . '/database.php');
import(__DIR__ . '/files.php');

set('keep_releases', 5);

add('exclude', [
    '.DS_Store',
    '/var/backups',
    '/package.json',
    '/package-lock.json',
    '/yarn.lock',
    '/.php-version',
    '/node_modules',
]);

before('deploy:publish', 'contao:install:lock');
before('deploy:publish', 'contao:manager:download');
after('contao:manager:download', 'contao:manager:lock');

after('deploy:failed', 'deploy:unlock');

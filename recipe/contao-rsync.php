<?php

namespace Deployer;

import('contrib/rsync.php');

set('rsync_dest', '{{release_path}}');

set('exclude', [
    '.git',
    '/.github',
    '/.idea',
    '/deploy.php',
    '/.env.local',
    '/.gitignore',
    '/config/parameters.yml',
    '/contao-manager',
    '/tests',
    '/var',
    '/vendor',
    '/app/Resources/contao/config/runonce*',
    '/assets',
    '/files',
    '/system',
    '/{{public_path}}/bundles',
    '/{{public_path}}/assets',
    '/{{public_path}}/files',
    '/{{public_path}}/share',
    '/{{public_path}}/system',
    '/{{public_path}}/app.php',
    '/{{public_path}}/app_dev.php',
    '/{{public_path}}/index.php',
    '/{{public_path}}/preview.php',
    '/{{public_path}}/robots.txt',
]);

set('rsync', function () {
    return [
        'exclude' => array_unique(get('exclude', [])),
        'exclude-file' => false,
        'include' => [],
        'include-file' => false,
        'filter' => [],
        'filter-file' => false,
        'filter-perdir' => false,
        'flags' => 'rz',
        'options' => ['delete'],
        'timeout' => 300,
    ];
});

desc('Use rsync task to pull project files');
task('deploy:update_code', function () {
    invoke('rsync');
});

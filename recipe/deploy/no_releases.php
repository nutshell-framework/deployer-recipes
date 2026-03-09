<?php

declare(strict_types=1);

namespace Deployer;

use function Deployer\Support\str_contains;

desc('Preparing host for deploy without releases');
task('deploy:setup', function () {
    // Check if shell is POSIX-compliant
    $result = run('echo $0');

    if (!str_contains($result, 'bash') && !str_contains($result, 'sh')) {
        throw new \RuntimeException('Shell on your server is not POSIX-compliant. Please change to sh, bash or similar.');
    }

    run('if [ ! -d {{deploy_path}} ]; then mkdir -p {{deploy_path}}; fi');

    // Create metadata .dep dir.
    run('cd {{deploy_path}} && if [ ! -d .dep ]; then mkdir .dep; fi');
})->hidden();

set('release_path', static function () {
    return get('deploy_path');
});

set('current_path', static function () {
    return get('deploy_path');
});

set('release_or_current_path', static function () {
    return get('deploy_path');
});

task('deploy:release', static function () {
    // No release folder is created.
})->hidden();

task('deploy:shared', static function () {
    // Shared links are not required without releases.
})->hidden();

task('deploy:symlink', static function () {
    // Symlink switching is not required without releases.
})->hidden();

task('deploy:cleanup', static function () {
    // Cleanup of old releases is not required without releases.
})->hidden();

task('rollback', static function () {
    // Rollback through previous releases is not possible without releases.
});

task('contao:manager:lock', static function () {
    cd('{{release_or_current_path}}');
    run('mkdir -p contao-manager');
    run('echo "99" > contao-manager/login.lock');
});

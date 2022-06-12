<?php

namespace Deployer;

use Deployer\Exception\ConfigurationException;

desc('Copy .htaccess files');
task('deploy:htaccess', function () {
    try {
        if ($htaccess = get('htaccess_filename')) {
            cd('{{release_path}}/{{public_path}}');

            run('if [ -f "./'.$htaccess.'" ]; then mv ./'.$htaccess.' ./.htaccess; fi');
            run('rm -f .htaccess_*');
        }
    } catch (ConfigurationException $e) {}
});

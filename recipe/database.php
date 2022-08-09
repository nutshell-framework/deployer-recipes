<?php

namespace Deployer;

desc('Downloads a database dump from given host and overrides the local database');
task('database:retrieve', static function () {
    $dumpFilename = sprintf('deployer__%s.sql.gz', date('YmdHis'));

    // Create backup
    cd('{{release_or_current_path}}');
    run("{{bin/console}} contao:backup:create '$dumpFilename'");
    info('Backup created on remote machine');

    // Download backup
    runLocally('mkdir -p var/backups');
    download("{{release_or_current_path}}/var/backups/$dumpFilename", 'var/backups/', ['progress_bar' => false]);
    info('Backup archive downloaded');

    // Restore backup
    runLocally("php vendor/bin/contao-console contao:backup:restore '$dumpFilename'");
    info('Local database restored');

    // Migrate database
    try {
        runLocally('php vendor/bin/contao-console contao:migrate --no-interaction --no-backup');
        info('Local database migrated');
    } catch (\Exception $e) {
        warning('Local database migration skipped');
    }
});

desc('Restores the local database on the given host');
task('database:release', static function () {
    $dumpFilename = sprintf('deployer__%s.sql.gz', date('YmdHis'));

    // Create backup
    runLocally("php vendor/bin/contao-console contao:backup:create '$dumpFilename'");
    info('Backup created on local machine');

    // Upload backup
    upload("var/backups/$dumpFilename", "{{release_or_current_path}}/var/backups/", ['progress_bar' => false]);
    info('Backup archive uploaded');

    // Restore backup
    cd('{{release_or_current_path}}');
    run("{{bin/console}} contao:backup:restore '$dumpFilename'");
    info('Remote database restored');

    // Migrate database
    try {
        run('{{bin/console}} contao:migrate {{console_options}} --no-backup');
        info('Remote database migrated');
    } catch (\Exception $e) {
        warning('Database migration skipped');
    }
});

task('ask_release', static function () {
    if (!askConfirmation('Remote (!) database will be overridden. OK?')) {
        die("Restore cancelled.\n");
    }
});

task('ask_retrieve', static function () {
    if (!askConfirmation('Local database will be overridden. OK?')) {
        die("Restore cancelled.\n");
    }
});

before('database:retrieve', 'ask_retrieve');
before('database:release', 'ask_release');

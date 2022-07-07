<?php

namespace Deployer;

// Retrieve files from remote (rsync)
desc('Downloads the files from remote.');
task('files:retrieve', static function () {
    download("{{release_or_current_path}}/files/", 'files/');
    info('Download of files/ directory completed');
});

task('ask_retrieve_files', static function () {
    if (!askConfirmation('Remote files will be downloaded (no deletes). OK?')) {
        die("Sync cancelled.\n");
    }
});

before('files:retrieve', 'ask_retrieve_files');

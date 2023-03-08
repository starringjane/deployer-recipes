<?php

namespace Deployer;

task('archive:archive', function () {
    if (has('previous_release')) {
        $archivePath = '{{deploy_path}}/archive';
        $archiveReleasePath = str_replace('/releases/', '/archive/', get('previous_release'));

        // Remove archived releases
        if (test("[ -d $archivePath ]")) {
            run("rm -rf $archivePath");
        }

        // Create empty archive
        run("mkdir -p $archivePath");

        // Move previous release to archive
        run("mv {{previous_release}} $archiveReleasePath");
    }
});

task('archive:unarchive', function () {
    // Move archived release to releases
    cd('{{deploy_path}}');

    // If there is no releases return empty list.
    if (!test('[ -d archive ] && [ "$(ls -A archive)" ]')) {
        return null;
    }

    // Will list only dirs in archive.
    $ll = explode("\n", run('cd archive && ls -t -1 -d */'));
    $ll = array_map(function ($release) {
        return basename(rtrim(trim($release), '/'));
    }, $ll);

    $releasesLog = get('releases_log');

    for ($i = count($releasesLog) - 1; $i >= 0; --$i) {
        $release = $releasesLog[$i]['release_name'];
        if (in_array($release, $ll, true)) {
            run("mv {{deploy_path}}/archive/$release {{deploy_path}}/releases/$release");
        }
    }
});

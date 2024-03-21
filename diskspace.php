<?php

namespace Deployer;

task('diskspace:check', function () {
    $available = get('disk_available_space');
    $availableReadable = diskspace_human_readable_size($available * 1024);

    $required = get('disk_required_release_space') * get('disk_required_release_space_multiplier');
    $requiredReadable = diskspace_human_readable_size($required * 1024);

    if ($available && $required && ($available < $required)) {
        throw new \Exception("Insufficient disk space available. Required: {$required} ($requiredReadable), Available: {$available} ($availableReadable)");
    }
});

set('disk_available_space', function () {
    $output = run('df --output="avail" {{ deploy_path }}');
    $lines = explode(PHP_EOL, $output);
    return (int) $lines[1];
});

set('disk_required_release_space', function () {
    $releasesPath = get('deploy_path') . '/releases';

    // Check of directory exists
    if (!test("[ -d $releasesPath ]")) {
        warning("Unable to check potential release size. Directory '$releasesPath' not found.");
        return 0;
    }

    /**
     * Get size of all releases
     * Example output
     * 973302   ./32
     * 984500   ./33
     * 1957802  .
     */
    $output = run("du -d 1 $releasesPath");

    // Get all sizes in an array without the parent directory
    $lines = explode(PHP_EOL, $output);
    array_pop($lines);

    // Map output lines to sizes in kb
    $sizes = array_map(function ($line) {
        return (int)explode("\t", $line)[0];
    }, $lines);

    // Check if any sizes were found
    if (empty($sizes)) {
        warning("Unable to check potential release size. No releases found in $releasesPath");
        return 0;
    }

    // Return size of biggest release
    return max($sizes);
});

set('disk_required_release_space_multiplier', function () {
    return 1.2;
});

function diskspace_human_readable_size($bytes, $dec = 2): string {
    $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor == 0) $dec = 0;
    return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
}

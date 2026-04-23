<?php

declare(strict_types=1);

/**
 * Legacy BSCS curriculum import entrypoint.
 *
 * Delegates to the generic JSON-driven importer with the local BSCS payload.
 *
 * Usage:
 *   php scripts/import_bscs_curriculum.php [--dry-run]
 */

$importerPath = __DIR__ . '/import_curriculum.php';
$payloadPath = __DIR__ . '/curriculum_bscs_2026.json';

if (!is_file($importerPath) || !is_file($payloadPath)) {
    fwrite(STDERR, "Missing BSCS import dependency.\n");
    exit(1);
}

$command = escapeshellarg(PHP_BINARY)
    . ' '
    . escapeshellarg($importerPath)
    . ' --file='
    . escapeshellarg($payloadPath);

if (in_array('--dry-run', $argv, true)) {
    $command .= ' --dry-run';
}

if (in_array('--help', $argv, true)) {
    $command .= ' --help';
}

passthru($command, $exitCode);

exit($exitCode);

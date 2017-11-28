<?php

/**
 * Felicity Migrate bootstrap
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use felicity\routing\Routing;
use felicity\migrate\Migrate;

Routing::cli(
    'migrate/list',
    [
        Migrate::class,
        'listMigrations'
    ],
    'felicityMigrate',
    'migrateListDesc'
);

Routing::cli(
    'migrate/up',
    [
        Migrate::class,
        'runMigrations'
    ],
    'felicityMigrate',
    'migrateUpDesc'
);

Routing::cli(
    'migrate/make',
    [
        Migrate::class,
        'makeMigration'
    ],
    'felicityMigrate',
    'migrateMakeDesc'
);

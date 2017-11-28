<?php

/**
 * Felicity Migrate bootstrap
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use felicity\config\Config;
use felicity\routing\Routing;
use felicity\migrate\commands\MigrateUpCommand;
use felicity\migrate\commands\MigrateListCommand;
use felicity\migrate\commands\MigrateMakeCommand;

Routing::cli(
    'migrate/list',
    [MigrateListCommand::class, 'run'],
    'felicityMigrate',
    'migrateListDesc'
);

Routing::cli(
    'migrate/up',
    [MigrateUpCommand::class, 'run'],
    'felicityMigrate',
    'migrateUpDesc'
);

Routing::cli(
    'migrate/make',
    [MigrateMakeCommand::class, 'run'],
    'felicityMigrate',
    'migrateMakeDesc'
);

Config::set('felicity.migrate.srcDir', __DIR__);

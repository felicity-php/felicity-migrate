<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use felicity\config\Config;

Config::set('lang.translations.en.felicityMigrate', [
    'migrateListDesc' => 'Lists migrations that need to run',
    'migrateUpDesc' => 'Runs migrations that need to run',
    'migrateMakeDesc' => 'Makes a migration. Use --description=MyDescription',
    'noMigrationsNeedToRun' => 'No migrations need to run',
    'followingMigrationsNeedToRun' => 'The following migrations need to be run',
    'group:' => 'Group:',
    'migrating' => 'Migrating...',
    'migratingGroup:' => 'Migrating Group:',
    'starting' => 'Starting',
    'finished' => 'Finished',
    'finishedMigratingGroup:' => 'Finished migrating group:',
    'migrationsComplete' => 'Migrations complete',
    'safeUpMethodNotFound' => '`safeUp` method not found on migration class',
    'migrationUnsuccessful' => 'Migration was unsuccessful. Aborting...',
]);

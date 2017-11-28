<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\commands;

use Pixie\Exception;
use ReflectionException;
use felicity\migrate\Migrate;
use felicity\translate\Translate;
use felicity\consoleoutput\ConsoleOutput;
use felicity\migrate\models\MigrationModel;
use felicity\migrate\models\MigrationGroupModel;

/**
 * Class ListMigrationsCommand
 */
class MigrateListCommand
{
    /**
     * Lists migrations that need to run
     * @throws Exception
     * @throws ReflectionException
     */
    public static function run()
    {
        $migrationGroups = Migrate::getMigrationsService()->getUnRunMigrations();

        $hasMigrations = false;

        foreach ($migrationGroups as $migrationGroup) {
            /** @var MigrationGroupModel $migrationGroup */

            if (! $migrationGroup->migrations->count()) {
                continue;
            }

            if (! $hasMigrations) {
                ConsoleOutput::write(
                    Translate::get(
                        'felicityMigrate',
                        'followingMigrationsNeedToRun'
                    ),
                    'green'
                );
            }

            $hasMigrations = true;

            ConsoleOutput::write('');

            ConsoleOutput::write(
                Translate::get(
                    'felicityMigrate',
                    'group:'
                ) . ' ',
                'green',
                false
            );

            ConsoleOutput::write("<bold>{$migrationGroup->name}</bold>");

            foreach ($migrationGroup->migrations->getModels() as $migrationModel) {
                /** @var MigrationModel $migrationModel */
                ConsoleOutput::write(
                    "  {$migrationModel->className}",
                    'yellow'
                );
            }
        }

        if (! $hasMigrations) {
            ConsoleOutput::write(
                Translate::get('felicityMigrate', 'noMigrationsNeedToRun'),
                'green'
            );

            return;
        }

        ConsoleOutput::write('');
    }
}

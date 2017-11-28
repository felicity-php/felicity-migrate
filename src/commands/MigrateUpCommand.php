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
 * Class RunMigrationsCommand
 */
class MigrateUpCommand
{
    /**
     * Runs migrations that need to run
     * @throws Exception
     * @throws ReflectionException
     * @throws \Exception
     */
    public static function run()
    {
        $migrationsService = Migrate::getMigrationsService();
        $migrationGroups = $migrationsService->getUnRunMigrations();
        $starting = Translate::get('felicityMigrate', 'starting');
        $finished = Translate::get('felicityMigrate', 'finished');
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
                        'migrating'
                    ),
                    'green'
                );
            }

            $hasMigrations = true;

            ConsoleOutput::write(
                Translate::get(
                    'felicityMigrate',
                    'migratingGroup:'
                ) . ' ',
                'yellow',
                false
            );

            ConsoleOutput::write(
                "<bold>{$migrationGroup->name}</bold>",
                'yellow',
                false
            );

            ConsoleOutput::write('...', 'yellow');

            foreach ($migrationGroup->migrations->getModels() as $migrationModel) {
                /** @var MigrationModel $migrationModel */
                ConsoleOutput::write(
                    "{$starting} {$migrationModel->className}...",
                    'yellow'
                );

                include_once $migrationModel->filePath;

                $migrationClass = new $migrationModel->className;

                if (! method_exists($migrationClass, 'safeUp')) {
                    throw new \Exception(Translate::get(
                        'felicityMigrate',
                        'safeUpMethodNotFound'
                    ));
                }

                if ($migrationClass->safeUp() !== true) {
                    ConsoleOutput::write(
                        '<bold>' . Translate::get(
                            'felicityMigrate',
                            'migrationUnsuccessful'
                        ) . '</bold>',
                        'red'
                    );
                    return;
                }

                $migrationsService->addCompleteMigration($migrationModel);

                /** @var MigrationModel $migrationModel */
                ConsoleOutput::write(
                    "{$finished} {$migrationModel->className}",
                    'green'
                );
            }

            ConsoleOutput::write(
                Translate::get(
                    'felicityMigrate',
                    'finishedMigratingGroup:'
                ) . ' ',
                'green',
                false
            );

            ConsoleOutput::write(
                "<bold>{$migrationGroup->name}</bold>",
                'green'
            );
        }

        if (! $hasMigrations) {
            ConsoleOutput::write(
                Translate::get('felicityMigrate', 'noMigrationsNeedToRun'),
                'green'
            );

            return;
        }

        ConsoleOutput::write(
            Translate::get('felicityMigrate', 'migrationsComplete'),
            'green'
        );
    }
}

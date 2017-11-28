<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate;

use Pixie\Exception;
use ReflectionException;
use felicity\config\Config;
use felicity\translate\Translate;
use felicity\architect\Architect;
use felicity\consoleoutput\ConsoleOutput;
use felicity\migrate\models\MigrationModel;
use felicity\migrate\services\MigrationsService;
use felicity\migrate\models\MigrationGroupModel;

/**
 * Class Migrate
 */
class Migrate
{
    /**
     * Lists migrations that need to run
     * @throws Exception
     * @throws ReflectionException
     */
    public static function listMigrations()
    {
        $migrationGroups = self::getMigrationsService()->getUnRunMigrations();

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

    /**
     * Lists migrations that need to run
     * @throws Exception
     * @throws ReflectionException
     * @throws \Exception
     */
    public static function runMigrations()
    {
        $migrationsService = self::getMigrationsService();

        $migrationGroups = $migrationsService->getUnRunMigrations();

        $hasMigrations = false;

        $starting = Translate::get('felicityMigrate', 'starting');

        $finished = Translate::get('felicityMigrate', 'finished');

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

            ConsoleOutput::write(
                '...',
                'yellow'
            );

            foreach ($migrationGroup->migrations->getModels() as $migrationModel) {
                /** @var MigrationModel $migrationModel */
                ConsoleOutput::write(
                    "{$starting} {$migrationModel->className}...",
                    'yellow'
                );

                include_once $migrationModel->filePath;

                $migrationClass = new $migrationModel->className;

                if (! method_exists($migrationClass, 'safeUp')) {
                    throw new \Exception(
                        Translate::get(
                            'felicityMigrate',
                            'safeUpMethodNotFound'
                        )
                    );
                }

                if ($migrationClass->safeUp() !== true) {
                    ConsoleOutput::write(
                        '<bold>' .
                        Translate::get(
                            'felicityMigrate',
                            'migrationUnsuccessful'
                        ) .
                        '</bold>',
                        'red'
                    );
                    exit();
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
            Translate::get(
                'felicityMigrate',
                'migrationsComplete'
            ),
            'green'
        );
    }

    /**
     * Lists migrations that need to run
     */
    public static function makeMigration()
    {
        ConsoleOutput::write('<bold>TODO: Make Migration</bold>', 'red');
    }

    /**
     * Gets the migrations service with dependency injection
     * @return MigrationsService
     * @throws Exception
     */
    public static function getMigrationsService() : MigrationsService
    {
        return new MigrationsService(new Architect(), Config::getInstance());
    }
}

<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\commands;

use Exception;
use felicity\config\Config;
use felicity\core\models\ArgumentsModel;
use felicity\consoleoutput\ConsoleOutput;
use felicity\migrate\Migrate;
use felicity\translate\Translate;

/**
 * Class MigrateMakeCommand
 */
class MigrateMakeCommand
{
    /**
     * Lists migrations that need to run
     * @param ArgumentsModel $argumentsModel
     * @throws Exception
     */
    public static function run(ArgumentsModel $argumentsModel)
    {
        $migrationsDir = rtrim(self::getMigrationsDir($argumentsModel), '/');

        if (! is_dir($migrationsDir)) {
            throw new Exception(
                Translate::get('felicityMigrate', 'migrationsDirDoesNotExist')
            );
        }

        if (! $migrationName = self::getMigrationsName($argumentsModel)) {
            throw new Exception(
                Translate::get('felicityMigrate', 'migrationNameRequired')
            );
        }

        $migrationsService = Migrate::getMigrationsService();

        $className = $migrationsService->getMigrationClassName($migrationName);

        ConsoleOutput::write(
            Translate::get('felicityMigrate', 'migrationWillBeCreatedAt:')
        );

        ConsoleOutput::write(
            "{$migrationsDir}/{$className}.php",
            'green'
        );

        $proceed = self::readInput('consoleProceed', 'yellow');

        if ($proceed !== 'y') {
            ConsoleOutput::write(
                Translate::get('felicityMigrate', 'aborting'),
                'red'
            );
            return;
        }

        Migrate::getMigrationsService()->makeMigration(
            $migrationsDir,
            $className
        );

        ConsoleOutput::write(
            Translate::get('felicityMigrate', 'migrationCreatedSuccessfully'),
            'green'
        );
    }

    /**
     * Reads user input
     * @param string $msg
     * @param string $color
     * @return string
     */
    private static function readInput(
        string $msg = null,
        string $color = ''
    ) : string {
        if ($msg !== null) {
            $msg = Translate::get('felicityMigrate', $msg);
            ConsoleOutput::write("{$msg} ", $color, false);
        }

        return trim(fgets(fopen('php://stdin', 'rb')));
    }

    /**
     * Gets the migration directory to use
     * @param ArgumentsModel $argumentsModel
     * @return string
     */
    private static function getMigrationsDir(
        ArgumentsModel $argumentsModel
    ) : string {
        if ($dir = $argumentsModel->getArgument('migrationsDir')) {
            return $dir;
        }

        if ($dir = $argumentsModel->getArgument('dir')) {
            return $dir;
        }

        if ($dir = Config::get('felicity.migrate.migrationsDir')) {
            return $dir;
        }

        return self::readInput('migrationsDirectory:');
    }

    /**
     * Gets the migration name to use
     * @param ArgumentsModel $argumentsModel
     * @return string
     */
    private static function getMigrationsName(
        ArgumentsModel $argumentsModel
    ) : string {
        if ($name = $argumentsModel->getArgument('description')) {
            return self::camelCase($name, true);
        }

        if ($name = $argumentsModel->getArgument('name')) {
            return self::camelCase($name, true);
        }

        return self::camelCase(self::readInput('migrationName:'), true);
    }

    /**
     * Make a string camel case
     * @param $str
     * @param bool $ucFirst
     * @param array $noStrip
     * @return string
     */
    public static function camelCase(
        string $str,
        bool $ucFirst = false,
        array $noStrip = []
    ) : string {
        // Non-alpha and non-numeric characters become spaces
        $str = preg_replace(
            '/[^a-z0-9' . implode('', $noStrip) . ']+/i',
            ' ',
            $str
        );

        // Trim the string up
        $str = trim($str);

        // Uppercase the first character of each word
        $str = ucwords($str);

        // Remove spaces
        $str = str_replace(' ', '', $str);

        // Return ucfirst if requested
        if ($ucFirst) {
            return ucfirst($str);
        }

        // Return lcfirst
        return lcfirst($str);
    }
}

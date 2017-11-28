<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate;

use felicity\config\Config;
use felicity\consoleoutput\ConsoleOutput;

/**
 * Class Migrate
 */
class Migrate
{
    /**
     * Lists migrations that need to run
     */
    public static function listMigrations()
    {
        ConsoleOutput::write('<bold>TODO: List Migrations</bold>', 'red');
    }

    /**
     * Lists migrations that need to run
     */
    public static function runMigrations()
    {
        ConsoleOutput::write('<bold>TODO: Run Migrations</bold>', 'red');
    }

    /**
     * Lists migrations that need to run
     */
    public static function makeMigration()
    {
        ConsoleOutput::write('<bold>TODO: Make Migration</bold>', 'red');
    }
}

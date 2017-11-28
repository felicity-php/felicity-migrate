<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\commands;

use felicity\consoleoutput\ConsoleOutput;

/**
 * Class MigrateMakeCommand
 */
class MigrateMakeCommand
{
    /**
     * Lists migrations that need to run
     */
    public static function run()
    {
        ConsoleOutput::write('<bold>TODO: Make Migration</bold>', 'red');
    }
}

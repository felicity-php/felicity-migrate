<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate;

use Pixie\Exception;
use felicity\config\Config;
use felicity\architect\Architect;
use felicity\migrate\services\MigrationsService;

/**
 * Class Migrate
 */
class Migrate
{
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

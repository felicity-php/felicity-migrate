<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\models;

use felicity\datamodel\Model;
use felicity\datamodel\ModelCollection;
use felicity\datamodel\services\datahandlers\StringHandler;
use felicity\datamodel\services\datahandlers\CollectionHandler;

/**
 * Class MigrationsService
 */
class MigrationGroupModel extends Model
{
    /** @var string $name */
    public $name;

    /** @var ModelCollection $migrations */
    public $migrations;

    /**
     * @inheritdoc
     */
    protected function defineHandlers(): array
    {
        return [
            'name' => [
                'class' => StringHandler::class,
            ],
            'migrations' => [
                'class' => CollectionHandler::class,
            ],
        ];
    }
}

<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\models;

use felicity\datamodel\Model;
use felicity\datamodel\services\datahandlers\StringHandler;

/**
 * Class MigrationModel
 */
class MigrationModel extends Model
{
    /** @var string $groupName */
    public $groupName;

    /** @var string $filePath */
    public $filePath;

    /** @var string $className */
    public $className;

    /**
     * @inheritdoc
     */
    protected function defineHandlers(): array
    {
        return [
            'groupName' => [
                'class' => StringHandler::class,
            ],
            'filePath' => [
                'class' => StringHandler::class,
            ],
            'className' => [
                'class' => StringHandler::class,
            ],
        ];
    }
}

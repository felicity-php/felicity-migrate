<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace felicity\migrate\services;

use Pixie\Exception;
use DirectoryIterator;
use ReflectionException;
use felicity\config\Config;
use felicity\architect\Architect;
use felicity\datamodel\ModelCollection;
use felicity\migrate\models\MigrationModel;
use felicity\migrate\models\MigrationGroupModel;

/**
 * Class MigrationsService
 */
class MigrationsService
{
    /** @var string TABLE_NAME */
    const TABLE_NAME = 'FelicityMigrations';

    /** @var Architect $architect */
    private $architect;

    /** @var Config $config */
    private $config;

    /**
     * MigrationsService constructor
     * @param Architect $architect
     * @param Config $config
     * @throws Exception
     */
    public function __construct(Architect $architect, Config $config)
    {
        $this->architect = $architect;
        $this->config = $config;

        $this->ensureMigrationSchemaExists();
    }

    /**
     * Ensures the migrations schema exists
     * @throws Exception
     */
    public function ensureMigrationSchemaExists()
    {
        if ($this->architect->getBuilder()->tableExists(self::TABLE_NAME)) {
            return;
        }

        $this->architect->getSchemaBuilder()->table(self::TABLE_NAME)
            ->string('group')
            ->string('migration')
            ->create();
    }

    /**
     * Gets all un-run migrations
     * @return ModelCollection
     * @throws ReflectionException
     * @throws Exception
     */
    public function getUnRunMigrations() : ModelCollection
    {
        /** @var array $groups */
        $groups = $this->config->getItem('felicity.migrate.locations', []);

        $groupModels = new ModelCollection();

        foreach ($groups as $name => $path) {
            $groupModels->addModel(
                $this->removeRunMigrationsFromGroup(
                    $this->getMigrationsFromPath($name, $path)
                )
            );
        }

        return $groupModels;
    }

    /**
     * Gets migrations from path
     * @param string $groupName
     * @param string $path
     * @return MigrationGroupModel
     * @throws ReflectionException
     */
    public function getMigrationsFromPath(
        string $groupName,
        string $path
    ) : MigrationGroupModel {
        $groupModel = new MigrationGroupModel([
            'name' => $groupName,
            'migrations' => new ModelCollection(),
        ]);

        if (! is_dir($path)) {
            return $groupModel;
        }

        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() ||
                ! $fileInfo->isFile() ||
                $fileInfo->getExtension() !== 'php'
            ) {
                continue;
            }

            $groupModel->migrations->addModel(new MigrationModel([
                'groupName' => $groupName,
                'filePath' => $fileInfo->getPathname(),
                'className' => $fileInfo->getBasename('.php'),
            ]));
        }

        return $groupModel;
    }

    /**
     * Removes run migrations from group
     * @param MigrationGroupModel $groupModel
     * @return MigrationGroupModel
     * @throws Exception
     */
    public function removeRunMigrationsFromGroup(
        MigrationGroupModel $groupModel
    ) : MigrationGroupModel {
        $migrationRecords = $this->architect->getBuilder()
            ->table(self::TABLE_NAME)
            ->select('migration')
            ->where('group', $groupModel->name)
            ->get();

        $names = [];

        foreach ($migrationRecords as $record) {
            $names[] = $record->migration;
        }

        foreach ($groupModel->migrations as $migration) {
            /** @var MigrationModel $migration */

            if (! \in_array($migration->className, $names, true)) {
                continue;
            }

            $groupModel->migrations->removeModel($migration);
        }

        return $groupModel;
    }

    /**
     * Adds a completed migration to the database
     * @param MigrationModel $migrationModel
     * @throws Exception
     */
    public function addCompleteMigration(MigrationModel $migrationModel)
    {
        $this->architect->getBuilder()->table(self::TABLE_NAME)
            ->insert([
                'group' => $migrationModel->groupName,
                'migration' => $migrationModel->className,
            ]);
    }
}

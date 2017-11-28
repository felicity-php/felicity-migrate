<?php

use felicity\architect\Architect;

/**
 * Class Migration
 */
class Migration
{
    /**
     * Runs migration
     * @return bool
     */
    public function safeUp() : bool
    {
        var_dump('TODO: create migration');
        die;
        Architect::schemaBuilder()->table('MyTestTable')
            ->string('testVarcharColumn')
            ->integer('testIntColumn')
            ->create();

        return true;
    }
}

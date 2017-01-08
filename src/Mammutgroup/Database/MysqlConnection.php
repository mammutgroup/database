<?php

namespace Mammutgroup\Database;

use Illuminate\Database\MySqlConnection as BaseMysqlConnection;

/**
 * Class MysqlConnection
 *
 * @package Mammutgroup\Database
 */
class MysqlConnection extends BaseMysqlConnection
{
    /**
     * Get a schema builder instance for the connection.
     *
     * @return Schema\Builder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new Query\Grammars\MysqlGrammar());
    }


    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new Schema\Grammars\MysqlGrammar());
    }


    /**
     * Get the default post processor instance.
     *
     * @return \Illuminate\Database\Query\Processors\MysqlProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new \Illuminate\Database\Query\Processors\MySqlProcessor;
    }
}

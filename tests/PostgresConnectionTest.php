<?php

use Mammutgroup\Database\MysqlConnection;
use Doctrine\DBAL\Driver\PDOPgSql\Driver;

class MysqlConnectionTest extends BaseTestCase
{
    public function testReturnsDoctrineDriver()
    {
        $conn = Mockery::mock(MysqlConnection::class)->makePartial();
        $this->assertInstanceOf(Driver::class, $conn->getDoctrineDriver());
    }
}

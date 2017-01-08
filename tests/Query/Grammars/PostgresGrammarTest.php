<?php

use Mammutgroup\Database\Query\Grammars\MysqlGrammar;
use Illuminate\Database\Query\Builder;

class MysqlGrammarTest extends BaseTestCase
{
    public function testHstoreWrapValue()
    {
        $grammar = Mockery::mock(MysqlGrammar::class)->makePartial();

        $this->assertEquals('a => b', $grammar->wrapValue('[a => b]'));
    }

    public function testJsonWrapValue()
    {
        $grammar = Mockery::mock(MysqlGrammar::class)->makePartial();

        $this->assertEquals('"a"->\'b\'', $grammar->wrapValue("a->'b'"));
        $this->assertEquals('"a"->>\'b\'', $grammar->wrapValue("a->>'b'"));
        $this->assertEquals('"a"#>\'b\'', $grammar->wrapValue("a#>'b'"));
        $this->assertEquals('"a"#>>\'b\'', $grammar->wrapValue("a#>>'b'"));
    }

    public function testWhereNotNull()
    {
        $grammar = Mockery::mock(MysqlGrammar::class)->makePartial();
        $builder = Mockery::mock(Builder::class);
        $where = [
            'column' => "a->>'b'"
        ];

        $this->assertEquals('("a"->>\'b\') is not null', $grammar->whereNotNull($builder, $where));
    }

    public function testWhereNull()
    {
        $grammar = Mockery::mock(MysqlGrammar::class)->makePartial();
        $builder = Mockery::mock(Builder::class);
        $where = [
            'column' => "a->>'b'"
        ];

        $this->assertEquals('("a"->>\'b\') is null', $grammar->whereNull($builder, $where));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use EsoftSk\DoctrineExtensionsBundle\Doctrine\QuoteStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QuoteStrategyTest extends TestCase
{
    public function testTableName(): void
    {
        $platformMock = $this->createPlatformMock();
        $quoteStrategy = new QuoteStrategy();

        $this->assertEquals(
            '"NoSchema"',
            $quoteStrategy->getTableName($this->createTableMock('NoSchema'), $platformMock)
        );

        $this->assertEquals(
            '"Schema"."WithSchema"',
            $quoteStrategy->getTableName($this->createTableMock('WithSchema', 'Schema'), $platformMock)
        );
    }

    public function testSequenceName(): void
    {
        $platformMock = $this->createPlatformMock();
        $quoteStrategy = new QuoteStrategy();

        $this->assertEquals(
            'Table_id_seq',
            $quoteStrategy->getSequenceName([
                'sequenceName' => 'Table_id_seq',
            ], $this->createTableMock('NoSchema'), $platformMock)
        );

        $this->assertEquals(
            '"Schema".Table_id_seq',
            $quoteStrategy->getSequenceName([
                'sequenceName' => 'Schema.Table_id_seq',
            ], $this->createTableMock('WithSchema', 'Schema'), $platformMock)
        );
    }

    public function testJoinTableName(): void
    {
        $platformMock = $this->createPlatformMock();
        $quoteStrategy = new QuoteStrategy();

        $this->assertEquals(
            '"NoSchema"',
            $quoteStrategy->getJoinTableName([
                'joinTable' => [
                    'name' => 'NoSchema'
                ]
            ], $this->createTableMock('Irrelevant'), $platformMock)
        );

        $this->assertEquals(
            '"Schema"."WithSchema"',
            $quoteStrategy->getJoinTableName([
                'joinTable' => [
                    'name' => 'Schema.WithSchema'
                ]
            ], $this->createTableMock('Irrelevant'), $platformMock)
        );
    }

    private function createTableMock(string $name, ?string $schema = null): MockObject
    {
        $mock = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->table = [
            'name' => $name
        ];

        if ($schema) {
            $mock->table['schema'] = $schema;
        }

        return $mock;
    }

    private function createPlatformMock(): MockObject
    {
        return $this
            ->getMockBuilder(AbstractPlatform::class)
            ->getMock();
    }
}

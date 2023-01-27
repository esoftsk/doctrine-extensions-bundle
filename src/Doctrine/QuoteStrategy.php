<?php

declare(strict_types=1);

namespace EsoftSk\DoctrineExtensionsBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata as OrmClassMetadata;
use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\Persistence\Mapping\ClassMetadata as PersistenceClassMetadata;

final class QuoteStrategy extends DefaultQuoteStrategy
{
    public function getTableName(PersistenceClassMetadata $class, AbstractPlatform $platform): string
    {
        $name = "\"{$class->table['name']}\""; // @phpstan-ignore-line

        if (!empty($class->table['schema'])) {
            $name = "\"{$class->table['schema']}\".$name";
        }

        return $name;
    }

    public function getSequenceName(array $definition, OrmClassMetadata $class, AbstractPlatform $platform): string
    {
        $definedName = $definition['sequenceName'];
        $tableName = $class->table['name'];

        $sequenceName = preg_replace("/($tableName.*)$/", '"$1"', $definedName);

        if (!empty($class->table['schema'])) {
            $sequenceName = str_replace($class->table['schema'], "\"{$class->table['schema']}\"", $sequenceName);
        }

        return $sequenceName;
    }

    public function getJoinTableName(array $association, OrmClassMetadata $class, AbstractPlatform $platform): string
    {
        return join('.', array_map(
                fn (string $part) => "\"$part\"",
                explode('.', $association['joinTable']['name'])
            )
        );
    }
}

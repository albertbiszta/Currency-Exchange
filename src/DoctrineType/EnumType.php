<?php

namespace App\DoctrineType;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LogicException;

abstract class EnumType extends Type
{
    public const NAME = '';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }
        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (false === enum_exists($this::getClass(), true)) {
            throw new LogicException("This class should be an enum");
        }
        return $this::getClass()::tryFrom($value);
    }

    abstract public static function getClass(): string;
}
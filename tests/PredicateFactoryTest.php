<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests;

use ju1ius\Footprints\Predicate;
use ju1ius\Footprints\Predicate\IsAnd;
use ju1ius\Footprints\Predicate\IsClass;
use ju1ius\Footprints\Predicate\IsFile;
use ju1ius\Footprints\Predicate\IsFunction;
use ju1ius\Footprints\Predicate\IsInternal;
use ju1ius\Footprints\Predicate\IsMethod;
use ju1ius\Footprints\Predicate\IsNamespace;
use ju1ius\Footprints\Predicate\IsNot;
use ju1ius\Footprints\Predicate\IsOr;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class PredicateFactoryTest extends TestCase
{
    public function testAnd(): void
    {
        Assert::assertInstanceOf(IsAnd::class, Predicate::and());
    }

    public function testIsOr(): void
    {
        Assert::assertInstanceOf(IsOr::class, Predicate::or());
    }

    public function testIsNot(): void
    {
        Assert::assertInstanceOf(IsNot::class, Predicate::not(fn() => true));
    }

    public function testIsClass(): void
    {
        Assert::assertInstanceOf(IsClass::class, Predicate::isClass());
    }

    public function testIsFile(): void
    {
        Assert::assertInstanceOf(IsFile::class, Predicate::isFile());
    }

    public function testIsFunction(): void
    {
        Assert::assertInstanceOf(IsFunction::class, Predicate::isFunction());
    }

    public function testIsMethod(): void
    {
        Assert::assertInstanceOf(IsMethod::class, Predicate::isMethod());
    }

    public function testIsNamespace(): void
    {
        Assert::assertInstanceOf(IsNamespace::class, Predicate::isNamespace());
    }

    public function testIsInternal(): void
    {
        Assert::assertInstanceOf(IsInternal::class, Predicate::isInternal());
    }
}

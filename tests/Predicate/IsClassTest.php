<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsClass;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsClassTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(array $classes, Frame $frame, bool $expected): void
    {
        $predicate = new IsClass(...$classes);
        Assert::assertSame($expected, $predicate($frame, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'no class name matches any class' => [
            [],
            new Frame('foo', class: 'Foo'),
            true,
        ];
        yield 'no class name doesnt match functions' => [
            [],
            new Frame('foo'),
            false,
        ];
        yield 'never matches functions' => [
            ['Foo'],
            new Frame('Foo'),
            false,
        ];
        yield 'matches classes' => [
            ['Foo'],
            new Frame('bar', class: 'Foo'),
            true,
        ];
        yield 'matches namespaced classes' => [
            ['Foo\\Bar'],
            new Frame('baz', class: 'Foo\\Bar'),
            true,
        ];
        yield 'doesnt match in other namespace' => [
            ['Bar'],
            new Frame('baz', class: 'Foo\\Bar'),
            false,
        ];
    }
}

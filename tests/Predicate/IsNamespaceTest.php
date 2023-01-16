<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsNamespace;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsNamespaceTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(array $namespaces, Frame $input, bool $expected): void
    {
        $predicate = new IsNamespace(...$namespaces);
        Assert::assertSame($expected, $predicate($input, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'no namespace matches any namespace' => [
            [],
            new Frame('<test>', 0, 'Foo\\bar'),
            true,
        ];
        yield 'no namespace doesnt match top level item' => [
            [],
            new Frame('<test>', 0, 'foo'),
            false,
        ];
        yield 'doesnt match function w/o namespace' => [
            ['Foo'],
            new Frame('<test>', 0, 'Foo'),
            false,
        ];
        yield 'doesnt match method w/o namespace' => [
            ['Foo'],
            new Frame('<test>', 0, 'bar', class: 'Foo'),
            false,
        ];
        yield 'doesnt match incomplete namespace prefix' => [
            ['Foo'],
            new Frame('<test>', 0, 'FooBar\\test'),
            false,
        ];
        yield 'matches namespaced function' => [
            ['Foo'],
            new Frame('<test>', 0, 'Foo\\bar'),
            true,
        ];
        yield 'matches namespaced method' => [
            ['Foo'],
            new Frame('<test>', 0, 'bar', class: 'Foo\\Bar'),
            true,
        ];
        yield 'matches sub-namespaces' => [
            ['Foo\\Bar'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar\\Baz'),
            true,
        ];
    }
}

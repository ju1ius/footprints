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
            new Frame('Foo\\bar'),
            true,
        ];
        yield 'no namespace doesnt match top level item' => [
            [],
            new Frame('foo'),
            false,
        ];
        yield 'doesnt match function w/o namespace' => [
            ['Foo'],
            new Frame('Foo'),
            false,
        ];
        yield 'doesnt match method w/o namespace' => [
            ['Foo'],
            new Frame('bar', 'Foo'),
            false,
        ];
        yield 'doesnt match incomplete namespace prefix' => [
            ['Foo'],
            new Frame('FooBar\\test'),
            false,
        ];
        yield 'matches namespaced function' => [
            ['Foo'],
            new Frame('Foo\\bar'),
            true,
        ];
        yield 'matches namespaced method' => [
            ['Foo'],
            new Frame('bar', 'Foo\\Bar'),
            true,
        ];
        yield 'matches sub-namespaces' => [
            ['Foo\\Bar'],
            new Frame('baz', 'Foo\\Bar\\Baz'),
            true,
        ];
    }
}

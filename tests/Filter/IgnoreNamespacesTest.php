<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\IgnoreNamespaces;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IgnoreNamespacesTest extends TestCase
{
    /**
     * @dataProvider ignoreNamespacesProvider
     */
    public function testIgnoreNamespaces(array $ignored, Frame $input, bool $expected): void
    {
        $filter = new IgnoreNamespaces(...$ignored);
        Assert::assertSame($expected, $filter($input, 0, []));
    }

    public static function ignoreNamespacesProvider(): iterable
    {
        yield 'empty filter' => [
            [],
            new Frame('<test>', 0, 'Foo\\bar'),
            true,
        ];
        yield 'doesnt match function w/o namespace' => [
            ['Foo'],
            new Frame('<test>', 0, 'Foo'),
            true,
        ];
        yield 'doesnt match method w/o namespace' => [
            ['Foo'],
            new Frame('<test>', 0, 'bar', class: 'Foo'),
            true,
        ];
        yield 'doesnt match incomplete namespace prefix' => [
            ['Foo'],
            new Frame('<test>', 0, 'FooBar\\test'),
            true,
        ];
        yield 'matches namespaced function' => [
            ['Foo'],
            new Frame('<test>', 0, 'Foo\\bar'),
            false,
        ];
        yield 'matches namespaced method' => [
            ['Foo'],
            new Frame('<test>', 0, 'bar', class: 'Foo\\Bar'),
            false,
        ];
        yield 'matches sub-namespaces' => [
            ['Foo\\Bar'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar\\Baz'),
            false,
        ];
    }
}

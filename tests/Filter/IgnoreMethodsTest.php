<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\IgnoreMethods;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IgnoreMethodsTest extends TestCase
{
    /**
     * @dataProvider ignoreMethodsProvider
     */
    public function testIgnoreMethods(array $ignored, Frame $input, bool $expected): void
    {
        $filter = new IgnoreMethods(...$ignored);
        Assert::assertSame($expected, $filter($input, 0, []));
    }

    public static function ignoreMethodsProvider(): iterable
    {
        yield 'empty filter' => [
            [],
            new Frame('<test>', 0, 'foo', class: 'Foo'),
            true,
        ];
        yield 'doesnt match functions' => [
            ['foo'],
            new Frame('<test>', 0, 'foo'),
            true,
        ];
        yield 'matches method' => [
            ['Foo->bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '->'),
            false,
        ];
        yield 'matches static method' => [
            ['Foo::bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '::'),
            false,
        ];
        yield 'matches namespaced method' => [
            ['Foo\\Bar->baz'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar', type: '->'),
            false,
        ];
        yield 'matches namespaced static method' => [
            ['Foo\\Bar::baz'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar', type: '::'),
            false,
        ];
    }
}

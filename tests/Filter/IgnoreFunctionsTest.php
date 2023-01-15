<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\IgnoreFunctions;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IgnoreFunctionsTest extends TestCase
{
    /**
     * @dataProvider ignoreFunctionsProvider
     */
    public function testItIgnoreFunctions(array $ignored, Frame $input, bool $expected): void
    {
        $filter = new IgnoreFunctions(...$ignored);
        Assert::assertSame($expected, $filter($input, 0, []));
    }

    public static function ignoreFunctionsProvider(): iterable
    {
        yield 'empty filter' => [
            [],
            new Frame('<test>', 0, 'foo'),
            true,
        ];
        yield 'excludes function' => [
            ['foo'],
            new Frame('<test>', 0, 'foo'),
            false,
        ];
        yield 'non-matching namespace' => [
            ['bar'],
            new Frame('<test>', 0, 'Foo\\bar'),
            true,
        ];
        yield 'matching namespace' => [
            ['Foo\\bar'],
            new Frame('<test>', 0, 'Foo\\bar'),
            false,
        ];
        yield 'never matches for methods' => [
            ['bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '->'),
            true,
        ];
        yield 'never matches for methods #2' => [
            ['Foo\\bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '->'),
            true,
        ];
    }
}

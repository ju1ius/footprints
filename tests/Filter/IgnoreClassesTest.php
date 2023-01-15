<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\IgnoreClasses;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IgnoreClassesTest extends TestCase
{
    /**
     * @dataProvider ignoreClassesProvider
     */
    public function testItIgnoresClasses(array $ignored, Frame $input, bool $expected): void
    {
        $filter = new IgnoreClasses(...$ignored);
        Assert::assertSame($expected, $filter($input, 0, []));
    }

    public static function ignoreClassesProvider(): iterable
    {
        yield 'empty filter' => [
            [],
            new Frame('<test>', 0, 'foo', class: 'Foo'),
            true,
        ];
        yield 'never matches functions' => [
            ['Foo'],
            new Frame('<test>', 0, 'Foo'),
            true,
        ];
        yield 'matches classes' => [
            ['Foo'],
            new Frame('<test>', 0, 'bar', class: 'Foo'),
            false,
        ];
        yield 'matches namespaced classes' => [
            ['Foo\\Bar'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar'),
            false,
        ];
        yield 'doesnt match in other namespace' => [
            ['Bar'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar'),
            true,
        ];
    }
}

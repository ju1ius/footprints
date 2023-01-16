<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsMethod;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsMethodTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(array $methods, Frame $input, bool $expected): void
    {
        $predicate = new IsMethod(...$methods);
        Assert::assertSame($expected, $predicate($input, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'no methods matches any method' => [
            [],
            new Frame('<test>', 0, 'foo', class: 'Foo'),
            true,
        ];
        yield 'no methods doesnt match functions' => [
            [],
            new Frame('<test>', 0, 'foo'),
            false,
        ];
        yield 'matches method' => [
            ['Foo->bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '->'),
            true,
        ];
        yield 'matches static method' => [
            ['Foo::bar'],
            new Frame('<test>', 0, 'bar', class: 'Foo', type: '::'),
            true,
        ];
        yield 'matches namespaced method' => [
            ['Foo\\Bar->baz'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar', type: '->'),
            true,
        ];
        yield 'matches namespaced static method' => [
            ['Foo\\Bar::baz'],
            new Frame('<test>', 0, 'baz', class: 'Foo\\Bar', type: '::'),
            true,
        ];
        yield 'doesnt match functions' => [
            ['foo'],
            new Frame('<test>', 0, 'foo'),
            false,
        ];
    }
}

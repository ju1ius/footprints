<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsFunction;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsFunctionTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(array $functions, Frame $input, bool $expected): void
    {
        $predicate = new IsFunction(...$functions);
        Assert::assertSame($expected, $predicate($input, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'no functions matches any function' => [
            [],
            new Frame('foo'),
            true,
        ];
        yield 'no functions doesnt match methods' => [
            [],
            new Frame('bar', 'Foo'),
            false,
        ];
        yield 'matches function' => [
            ['foo'],
            new Frame('foo'),
            true,
        ];
        yield 'non-matching namespace' => [
            ['bar'],
            new Frame('Foo\\bar'),
            false,
        ];
        yield 'matching namespace' => [
            ['Foo\\bar'],
            new Frame('Foo\\bar'),
            true,
        ];
        yield 'never matches for methods' => [
            ['bar'],
            new Frame('bar', class: 'Foo', type: '->'),
            false,
        ];
        yield 'never matches for methods #2' => [
            ['Foo\\bar'],
            new Frame('bar', class: 'Foo', type: '->'),
            false,
        ];
    }
}

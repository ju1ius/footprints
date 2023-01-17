<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsFile;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsFileTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(array $globs, Frame $input, bool $expected): void
    {
        $predicate = new IsFile(...$globs);
        Assert::assertSame($expected, $predicate($input, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'no globs matches anything' => [
            [],
            new Frame('foo', file: 'foo.php'),
            true,
        ];
        yield 'internal functions never match' => [
            [],
            new Frame('foo'),
            false,
        ];
        yield 'no match' => [
            ['*.html'],
            new Frame('foo', file: '/vendor/foo/bar.php'),
            false,
        ];
        yield 'exact match' => [
            ['/vendor/foo/bar.php'],
            new Frame('foo', file: '/vendor/foo/bar.php'),
            true,
        ];
        yield 'star match' => [
            ['*.php'],
            new Frame('foo', file: '/vendor/foo/bar.php'),
            true,
        ];
        yield 'star match directory' => [
            ['/vendor/*'],
            new Frame('foo', file: '/vendor/foo/bar.php'),
            true,
        ];
    }
}

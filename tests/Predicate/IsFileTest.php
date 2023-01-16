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
            new Frame('foo.php', 0, 'foo'),
            true,
        ];
        yield 'no match' => [
            ['*.html'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            false,
        ];
        yield 'exact match' => [
            ['/vendor/foo/bar.php'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            true,
        ];
        yield 'star match' => [
            ['*.php'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            true,
        ];
        yield 'star match directory' => [
            ['/vendor/*'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            true,
        ];
    }
}

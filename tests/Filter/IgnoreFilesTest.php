<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\IgnoreFiles;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IgnoreFilesTest extends TestCase
{
    /**
     * @dataProvider ignoreFilesProvider
     */
    public function testIgnoreFiles(array $ignored, Frame $input, bool $expected): void
    {
        $filter = new IgnoreFiles(...$ignored);
        Assert::assertSame($expected, $filter($input, 0, []));
    }

    public static function ignoreFilesProvider(): iterable
    {
        yield 'no match' => [
            ['*.html'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            true,
        ];
        yield 'exact match' => [
            ['/vendor/foo/bar.php'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            false,
        ];
        yield 'star match' => [
            ['*.php'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            false,
        ];
        yield 'star match directory' => [
            ['/vendor/*'],
            new Frame('/vendor/foo/bar.php', 0, 'foo'),
            false,
        ];
    }
}

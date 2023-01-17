<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsInternal;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsInternalTest extends TestCase
{
    /**
     * @dataProvider predicateProvider
     */
    public function testPredicate(Frame $frame, bool $expected): void
    {
        $predicate = new IsInternal();
        Assert::assertSame($expected, $predicate($frame, 0, []));
    }

    public static function predicateProvider(): iterable
    {
        yield 'matches internal frames' => [
            new Frame('foo'),
            true,
        ];
        yield 'doesnt match non-internal frames' => [
            new Frame('foo', file: 'foo.php'),
            false,
        ];
    }
}

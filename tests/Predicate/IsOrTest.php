<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsOr;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsOrTest extends TestCase
{
    public function testPredicate(): void
    {
        $frame = new Frame('foo', null, null, '<test>', 0);

        $predicate = new IsOr(fn() => false, fn() => false);
        Assert::assertFalse($predicate($frame, 0, []));

        $predicate = new IsOr(fn() => false, fn() => true, fn() => false);
        Assert::assertTrue($predicate($frame, 0, []));
    }
}

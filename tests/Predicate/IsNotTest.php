<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsNot;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsNotTest extends TestCase
{
    public function testPredicate(): void
    {
        $notTrue = new IsNot(fn() => true);
        $notFalse = new IsNot(fn() => false);
        $frame = new Frame('foo', null, null, '<test>', 0);

        Assert::assertFalse($notTrue($frame, 0, []));
        Assert::assertTrue($notFalse($frame, 0, []));
    }
}

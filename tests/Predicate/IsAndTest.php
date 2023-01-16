<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\Predicate\IsAnd;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsAndTest extends TestCase
{
    public function testPredicate(): void
    {
        $true = fn() => true;
        $false = fn() => false;
        $frame = new Frame('<test>', 0, 'foo');

        $predicate = new IsAnd($true, $true);
        Assert::assertTrue($predicate($frame, 0, []));

        $predicate = new IsAnd($true, $false, $true);
        Assert::assertFalse($predicate($frame, 0, []));
    }
}

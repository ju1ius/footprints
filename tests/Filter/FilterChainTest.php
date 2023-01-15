<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests\Filter;

use ju1ius\Footprints\Filter\FilterChain;
use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;
use PHPUnit\Framework\TestCase;

final class FilterChainTest extends TestCase
{
    public function testAllPredicatesAreCalled(): void
    {
        $frame = new Frame('<test>', 0, 'foo');

        $filter = self::createMock(FrameFilter::class);
        $filter->method('__invoke')
            ->willReturn(true, true, true)
        ;
        $filter->expects(self::exactly(3))
            ->method('__invoke')
            ->with($frame, 0, [])
        ;

        $chain = new FilterChain($filter, $filter, $filter);
        $chain($frame, 0, []);
    }

    public function testChainStopsAtFirstFalsyPredicate(): void
    {
        $frame = new Frame('<test>', 0, 'foo');

        $filter = self::createMock(FrameFilter::class);
        $filter->method('__invoke')
            ->willReturn(true, false, true)
        ;
        $filter->expects(self::exactly(2))
            ->method('__invoke')
            ->with($frame, 0, [])
        ;

        $chain = new FilterChain($filter, $filter, $filter);
        $chain($frame, 0, []);
    }
}

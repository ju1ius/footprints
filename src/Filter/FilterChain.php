<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;

final class FilterChain implements FrameFilter
{
    /**
     * @var array<callable(Frame, int, Frame[]): bool>
     */
    private readonly array $predicates;

    /**
     * @param callable(Frame, int, Frame[]): bool ...$predicates
     */
    public function __construct(callable ...$predicates)
    {
        $this->predicates = $predicates;
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        foreach ($this->predicates as $predicate) {
            if (!$predicate($frame, $index, $stack)) {
                return false;
            }
        }

        return true;
    }
}

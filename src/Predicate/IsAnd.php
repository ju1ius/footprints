<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;

final class IsAnd implements FrameFilter
{
    private readonly array $predicates;

    /**
     * @param callable(Frame, int, Frame[]):bool ...$predicates
     */
    public function __construct(callable ...$predicates)
    {
        $this->predicates = $predicates;
    }

    /**
     * @inheritDoc
     */
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

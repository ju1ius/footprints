<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;

final class IsNot implements PredicateInterface
{
    private readonly \Closure $predicate;

    public function __construct(
        callable $predicate,
    ) {
        $this->predicate = $predicate(...);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        return !($this->predicate)($frame, $index, $stack);
    }
}

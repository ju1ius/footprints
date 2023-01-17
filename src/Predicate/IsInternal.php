<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;

final class IsInternal implements PredicateInterface
{
    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        return $frame->file === null;
    }
}

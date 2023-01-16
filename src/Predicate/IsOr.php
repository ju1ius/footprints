<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;

final class IsOr implements PredicateInterface
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
            if ($predicate($frame, $index, $stack)) {
                return true;
            }
        }

        return false;
    }
}

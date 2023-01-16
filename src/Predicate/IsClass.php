<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;

final class IsClass implements PredicateInterface
{
    /**
     * @var array<string, true>
     */
    private readonly array $classes;

    public function __construct(string ...$classes)
    {
        $this->classes = array_fill_keys($classes, true);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if ($class = $frame->class) {
            if (!$this->classes) {
                return true;
            }
            return $this->classes[$class] ?? false;
        }

        return false;
    }
}

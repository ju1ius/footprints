<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;

final class IsNamespace implements PredicateInterface
{
    /**
     * @var string[]
     */
    private array $namespaces;

    public function __construct(string ...$namespaces)
    {
        $this->namespaces = array_map(
            fn($ns) => trim($ns, '\\') . '\\',
            $namespaces,
        );
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if (!$frame->namespace) {
            return false;
        }

        if (!$this->namespaces) {
            return true;
        }

        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($frame->namespace . '\\', $namespace)) {
                return true;
            }
        }

        return false;
    }
}

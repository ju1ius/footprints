<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameType;
use ju1ius\Footprints\PredicateInterface;

final class IsFunction implements PredicateInterface
{
    /**
     * @var array<string, true>
     */
    private readonly array $functions;

    public function __construct(string ...$functions)
    {
        $this->functions = array_fill_keys($functions, true);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if ($frame->type === FrameType::Function) {
            if (!$this->functions) {
                return true;
            }
            return $this->functions[$frame->function] ?? false;
        }

        return false;
    }
}

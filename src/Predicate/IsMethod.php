<?php declare(strict_types=1);

namespace ju1ius\Footprints\Predicate;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\PredicateInterface;
use ju1ius\Footprints\FrameType;

final class IsMethod implements PredicateInterface
{
    /**
     * @var array<string, true>
     */
    private readonly array $methods;

    public function __construct(string ...$methods)
    {
        $this->methods = array_fill_keys($methods, true);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        return match ($frame->type) {
            FrameType::Method => (
                !$this->methods
                || $this->methods["{$frame->class}->{$frame->function}"] ?? false
            ),
            FrameType::StaticMethod => (
                !$this->methods
                || $this->methods["{$frame->class}::{$frame->function}"] ?? false
            ),
            default => false,
        };
    }
}

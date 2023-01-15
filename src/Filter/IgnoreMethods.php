<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;
use ju1ius\Footprints\FrameType;

final class IgnoreMethods implements FrameFilter
{
    /**
     * @var array<string, false>
     */
    private readonly array $methods;

    public function __construct(string ...$methods)
    {
        $this->methods = array_fill_keys($methods, false);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if (!$this->methods) {
            return true;
        }

        return match ($frame->type) {
            FrameType::Method => $this->methods["{$frame->class}->{$frame->function}"] ?? true,
            FrameType::StaticMethod => $this->methods["{$frame->class}::{$frame->function}"] ?? true,
            default => true,
        };
    }
}

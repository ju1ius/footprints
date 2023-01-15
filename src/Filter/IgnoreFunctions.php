<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;
use ju1ius\Footprints\FrameType;

final class IgnoreFunctions implements FrameFilter
{
    /**
     * @var array<string, false>
     */
    private readonly array $functions;

    public function __construct(string ...$functions)
    {
        $this->functions = array_fill_keys($functions, false);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        return match ($frame->type) {
            FrameType::Function => $this->functions[$frame->function] ?? true,
            default => true,
        };

        if ($frame->class) {
            return true;
        }

        return $this->functions[$frame->function] ?? true;
    }
}

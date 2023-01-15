<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;

final class IgnoreClasses implements FrameFilter
{
    /**
     * @var array<string, false>
     */
    private readonly array $classes;

    public function __construct(string ...$classes)
    {
        $this->classes = array_fill_keys($classes, false);
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if ($class = $frame->class) {
            return $this->classes[$class] ?? true;
        }

        return true;
    }
}

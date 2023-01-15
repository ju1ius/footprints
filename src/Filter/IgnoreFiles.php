<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;

final class IgnoreFiles implements FrameFilter
{
    /**
     * @var string[]
     */
    private readonly array $globs;

    public function __construct(string ...$globs)
    {
        $this->globs = $globs;
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        foreach ($this->globs as $glob) {
            if (fnmatch($glob, $frame->file)) {
                return false;
            }
        }

        return true;
    }
}

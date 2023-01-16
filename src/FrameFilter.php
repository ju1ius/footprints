<?php declare(strict_types=1);

namespace ju1ius\Footprints;

interface FrameFilter
{
    /**
     * @param Frame[] $stack
     * @return bool `true` if the frame should be kept, `false` if the frame should be removed.
     */
    public function __invoke(Frame $frame, int $index, array $stack): bool;
}

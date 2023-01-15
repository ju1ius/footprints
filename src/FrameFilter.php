<?php declare(strict_types=1);

namespace ju1ius\Footprints;

interface FrameFilter
{
    /**
     * @param Frame[] $stack
     */
    public function __invoke(Frame $frame, int $index, array $stack): bool;
}

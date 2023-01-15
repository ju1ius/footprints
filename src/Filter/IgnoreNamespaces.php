<?php declare(strict_types=1);

namespace ju1ius\Footprints\Filter;
use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameFilter;

final class IgnoreNamespaces implements FrameFilter
{
    /**
     * @var string[]
     */
    private array $namespaces;

    public function __construct(string ...$namespaces)
    {
        $this->namespaces = array_map(
            fn($ns) => rtrim($ns, '\\') . '\\',
            $namespaces,
        );
    }

    public function __invoke(Frame $frame, int $index, array $stack): bool
    {
        if (!$frame->namespace) {
            return true;
        }

        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($frame->namespace . '\\', $namespace)) {
                return false;
            }
        }

        return true;
    }
}

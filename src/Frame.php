<?php declare(strict_types=1);

namespace ju1ius\Footprints;

use Stringable;

final class Frame implements Stringable
{
    public readonly FrameType $type;
    public readonly ?string $namespace;
    private readonly ?string $sep;

    public function __construct(
        public readonly string $file,
        public readonly int $line,
        public readonly string $function,
        public readonly ?string $class = null,
        ?string $type = null,
        public readonly ?object $object = null,
        public readonly array $args = [],
    ) {
        $this->namespace = match ($class) {
            null => $this->extractNamespace($function),
            default => $this->extractNamespace($class)
        };
        $this->type = match ($function) {
            '{closure}' => FrameType::Closure,
            default => match ($class) {
                null => FrameType::Function,
                default => match ($type) {
                    '::' => FrameType::StaticMethod,
                    default => FrameType::Method,
                },
            },
        };
        $this->sep = $type;
    }

    public static function of(array|self $frame): self
    {
        if (\is_array($frame)) {
            return new self(...$frame);
        }

        return $frame;
    }

    private function extractNamespace(string $fqn): ?string
    {
        return match ($p = strrpos($fqn, '\\')) {
            0, false => null,
            default => substr($fqn, 0, $p),
        };
    }

    public function __toString(): string
    {
        if (($class = $this->class) && str_starts_with($class, 'class@anonymous')) {
            $class = 'class@anonymous';
        }
        $args = [];
        if ($this->args) {
            foreach ($this->args as $arg) {
                $args[] = match (true) {
                    \is_null($arg) => 'NULL',
                    \is_bool($arg) => $arg ? 'true' : 'false',
                    \is_array($arg) => 'Array',
                    \is_object($arg) => sprintf('Object(%s)', get_class($arg)),
                    \is_string($arg) => "'...'",
                    default => (string)$arg,
                };
            }
        }

        return sprintf(
            '%s(%d): %s%s%s(%s)',
            $this->file,
            $this->line,
            $class ?? '',
            $this->sep ?? '',
            $this->function,
            $args ? implode(', ', $args) : '',
        );
    }
}

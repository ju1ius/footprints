<?php declare(strict_types=1);

namespace ju1ius\Footprints;

use Countable;
use IteratorAggregate;
use Stringable;
use Traversable;

final class Backtrace implements IteratorAggregate, Countable, Stringable
{
    public const PROVIDE_OBJECT = \DEBUG_BACKTRACE_PROVIDE_OBJECT;
    public const IGNORE_ARGS = \DEBUG_BACKTRACE_IGNORE_ARGS;

    private function __construct(
        /** @var Frame[] */
        private array $frames,
    ) {
    }

    public static function of(array|self $trace): self
    {
        if (\is_array($trace)) {
            return new self(array_map(Frame::of(...), $trace));
        }
        return $trace;
    }

    public static function capture(int $offset = 0, int $flags = 0): self
    {
        $frames = array_slice(\debug_backtrace($flags), abs($offset) + 1);
        return self::of($frames);
    }

    public static function fromThrowable(\Throwable $subject, int $offset = 0): self
    {
        $frames = array_slice($subject->getTrace(), abs($offset));
        return self::of($frames);
    }

    /**
     * @return Frame[]
     */
    public function frames(): array
    {
        return $this->frames;
    }

    public function top(): ?Frame
    {
        return $this->frames[0] ?? null;
    }

    public function bottom(): ?Frame
    {
        return end($this->frames) ?: null;
    }

    /**
     * @param callable(Frame, int, Frame[]):bool $predicate
     * @return self
     */
    public function filter(callable $predicate): self
    {
        $frames = [];
        foreach ($this->frames as $index => $frame) {
            if ($predicate($frame, $index, $this->frames)) {
                $frames[] = $frame;
            }
        }

        return new self($frames);
    }

    public function map(callable $predicate): self
    {
        $frames = [];
        foreach ($this->frames as $index => $frame) {
            $frames[] = $predicate($frame, $index, $this->frames);
        }

        return new self($frames);
    }

    public function getIterator(): Traversable
    {
        yield from $this->frames;
    }

    public function count(): int
    {
        return count($this->frames);
    }

    public function __toString(): string
    {
        $output = '';
        foreach ($this->frames as $index => $frame) {
            $output .= "#{$index} {$frame}\n";
        }

        return $output;
    }
}

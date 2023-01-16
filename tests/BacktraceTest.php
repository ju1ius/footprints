<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests;

use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Frame;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class BacktraceTest extends TestCase
{
    public function testOfIdentity(): void
    {
        $bt = Backtrace::of([]);
        Assert::assertSame($bt, Backtrace::of($bt));
    }

    public function testItCaptureFrames(): void
    {
        $bt = Backtrace::capture()->frames();
        $top = $bt[0];
        Assert::assertInstanceOf(Frame::class, $top);
        Assert::assertSame(__CLASS__, $top->class);
        Assert::assertSame(__FUNCTION__, $top->function);
    }

    public function testItCapturesObjects(): void
    {
        $frames = Backtrace::capture(0, Backtrace::PROVIDE_OBJECT)->frames();
        $top = $frames[0];
        Assert::assertSame($this, $top->object);
    }

    public function testItCanSkipFrames(): void
    {
        $frames = (fn() => Backtrace::capture(1)->frames())();
        $top = $frames[0];
        Assert::assertSame(__CLASS__, $top->class);
        Assert::assertSame(__FUNCTION__, $top->function);
    }

    public function testItCapturesExceptionFrames(): void
    {
        $throw = fn($msg) => throw new \RuntimeException($msg);
        try {
            $throw('test');
        } catch (\RuntimeException $err) {
            $frames = Backtrace::fromThrowable($err, 1)->frames();
            $top = $frames[0];
            Assert::assertSame(__CLASS__, $top->class);
            Assert::assertSame(__FUNCTION__, $top->function);
        }
    }

    public function testTopBottomCount(): void
    {
        $bt = Backtrace::capture();
        $frames = $bt->frames();
        Assert::assertSame($frames[0], $bt->top());
        Assert::assertSame(end($frames), $bt->bottom());
        Assert::assertCount(count($frames), $bt);
    }

    public function testGetIterator(): void
    {
        $bt = Backtrace::capture();
        Assert::assertSame($bt->frames(), iterator_to_array($bt));
    }

    public function testAccept(): void
    {
        $bt = Backtrace::capture();
        Assert::assertNotEmpty($bt->accept(fn() => true)->frames());
        Assert::assertEmpty($bt->accept(fn() => false)->frames());
    }

    public function testReject(): void
    {
        $bt = Backtrace::capture();
        Assert::assertNotEmpty($bt->reject(fn() => false)->frames());
        Assert::assertEmpty($bt->reject(fn() => true)->frames());
    }

    public function testMap(): void
    {
        $bt = Backtrace::capture();
        Assert::assertSame($bt->frames(), $bt->map(fn($f) => $f)->frames());
    }

    public function testToString(): void
    {
        $foo = new class {
            public function bar(object $o, bool $b, string $s, int $i, float $f, array $a): array
            {
                $bt = Backtrace::capture();
                ob_start();
                \debug_print_backtrace();
                return [$bt, ob_get_clean()];
            }
        };
        [$bt, $expected] = $foo->bar($this, true, "foo", 42, 66.6, []);
        Assert::assertSame($expected, (string)$bt);
    }
}

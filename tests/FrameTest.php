<?php declare(strict_types=1);

namespace ju1ius\Footprints\Tests;

use ju1ius\Footprints\Frame;
use ju1ius\Footprints\FrameType;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class FrameTest extends TestCase
{
    public function testOfIdentity(): void
    {
        $frame = new Frame('<test>', 0, 'foo');
        Assert::assertSame($frame, Frame::of($frame));
    }

    /**
     * @dataProvider frameTypeProvider
     */
    public function testFrameType(array $args, FrameType $expected): void
    {
        $frame = Frame::of(array_merge($args, [
            'file' => '<test>',
            'line' => 0,
        ]));
        Assert::assertSame($expected, $frame->type);
    }

    public static function frameTypeProvider(): iterable
    {
        yield 'function' => [
            ['function' => 'foo'],
            FrameType::Function,
        ];
        yield 'method' => [
            ['function' => 'foo', 'class' => 'Foo'],
            FrameType::Method,
        ];
        yield 'static method' => [
            ['function' => 'foo', 'class' => 'Foo', 'type' => '::'],
            FrameType::StaticMethod,
        ];
        yield 'closure' => [
            ['function' => '{closure}'],
            FrameType::Closure,
        ];
        yield 'closure in method' => [
            ['function' => '{closure}', 'class' => 'Foo', 'type' => '->'],
            FrameType::Closure,
        ];
        yield 'closure in static method' => [
            ['function' => '{closure}', 'class' => 'Foo', 'type' => '::'],
            FrameType::Closure,
        ];
    }
}

<?php declare(strict_types=1);

namespace ju1ius\Footprints;

enum FrameType
{
    case Function;
    case Closure;
    case Method;
    case StaticMethod;
}

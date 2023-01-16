<?php declare(strict_types=1);

namespace ju1ius\Footprints;

use ju1ius\Footprints\Predicate\IsAnd;
use ju1ius\Footprints\Predicate\IsClass;
use ju1ius\Footprints\Predicate\IsFile;
use ju1ius\Footprints\Predicate\IsFunction;
use ju1ius\Footprints\Predicate\IsMethod;
use ju1ius\Footprints\Predicate\IsNamespace;
use ju1ius\Footprints\Predicate\IsNot;
use ju1ius\Footprints\Predicate\IsOr;

final class Predicate
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct() {}

    /**
     * @param callable(Frame, int, Frame[]):bool ...$predicates
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function and(callable ...$predicates): callable
    {
        return new IsAnd(...$predicates);
    }

    /**
     * @param callable(Frame, int, Frame[]):bool ...$predicates
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function or(callable ...$predicates): callable
    {
        return new IsOr(...$predicates);
    }

    /**
     * @param callable(Frame, int, Frame[]):bool ...$predicates
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function not(callable ...$predicates): callable
    {
        return new IsNot(...$predicates);
    }

    /**
     * @param string ...$classes
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function isClass(string ...$classes): callable
    {
        return new IsClass(...$classes);
    }

    /**
     * @param string ...$globs
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function isFile(string ...$globs): callable
    {
        return new IsFile(...$globs);
    }

    /**
     * @param string ...$functions
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function isFunction(string ...$functions): callable
    {
        return new IsFunction(...$functions);
    }

    /**
     * @param string ...$methods
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function isMethod(string ...$methods): callable
    {
        return new IsMethod(...$methods);
    }

    /**
     * @param string ...$namespaces
     * @return callable(Frame, int, Frame[]):bool
     */
    public static function isNamespace(string ...$namespaces): callable
    {
        return new IsNamespace(...$namespaces);
    }
}

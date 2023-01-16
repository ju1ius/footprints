# ju1ius/footprints

[![codecov](https://codecov.io/gh/ju1ius/footprints/branch/main/graph/badge.svg?token=0J6QBKR13X)](https://codecov.io/gh/ju1ius/footprints)

Filterable backtraces for PHP

## Installation

```sh
composer require ju1ius/footprints
```

## Usage

### Capturing stack traces

```php
use ju1ius\Footprints\Backtrace;

// Capture the current stack trace
$trace = Backtrace::capture();
// retrieve the array of stack frames
$frames = $trace->frames();

// Capture the current stack trace, skipping the current stack frame
$trace = Backtrace::capture(1);

// Second argument is the flags for \debug_backtrace()
$trace = Backtrace::capture(0, Backtrace::PROVIDE_OBJECT|Backtrace::IGNORE_ARGS);

// You can capture error/exception traces too
try {
    // ...
} catch (\Throwable $err) {
    // Capture the exception trace, skipping the two topmost frames
    $trace = Backtrace::captureThrowable($err, 2);
}
```

### Filtering stack traces

To filter backtraces, the `Backtrace::accept()` and `Backtrace::reject()` methods are available,
both of which accept a `callable` predicate and returns a filtered `Backtrace` object.

The `$predicate` argument has the following signature: `callable(Frame, int, Frame[]): bool`:
it takes a Frame, it's index and the whole frames array and returns a boolean indicating
whether the predicate matched.

`Backtrace::accept()` will accept (keep) the frames for which `$predicate` returns a truthy value,
while `Backtrace::reject()` will reject (filter-out) the frames for which `$predicate` returns a truthy value.

```php
use ju1ius\Footprints\Backtrace;

// Keep only frames for:
// * the top-level foo() function
// * any method named foo() regardless of it's class.
$trace = Backtrace::capture()
    ->accept(fn(Frame $frame) => $frame->function === 'foo');

// Filters out frames for:
// * the top-level foo() function
// * any method named foo() regardless of it's class.
$trace = Backtrace::capture()
    ->reject(fn(Frame $frame) => $frame->function === 'foo');
```

For convenience, this library comes with a few built-in predicates.

### Builtin predicates

#### IsFunction(string ...$functionNames)

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\IsFunction;

$trace = Backtrace::capture()->reject(new IsFunction(
    'foo',
    'Acme\\foobar',
));
```

#### IsClass(string ...$classNames)

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\IsClass;

$trace = Backtrace::capture()->reject(new IsClass(
    'Foo',
    'Acme\\FooBar',
));
```

#### IsMethod(string ...$methodNames)

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\IsMethod;

$trace = Backtrace::capture()->reject(new IsMethod(
    // rejects method `bar` of class `Foo`
    'Foo->bar',
    // rejects static method `baz` of class `Acme\FooBar`
    'Acme\\FooBar::baz',
));
```

#### IsNamespace(string ...$namespaces)

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\IsNamespace;

$trace = Backtrace::capture()->reject(new IsNamespace(
    // rejects everything in namespace `Acme\Foo` and all it's sub-namespaces.
    'Acme\\Foo',
));
```

#### IsFile(string ...$globPatterns)

The `IsFile` predicate accepts glob patterns in the syntax accepted by `fnmatch`.

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\IsFile;

$trace = Backtrace::capture()->reject(new IsFile(
    // rejects everything in `/src/foo.php`
    '/src/foo.php',
    // rejects everything in the `/vendor` directory
    '/vendor/*',
    // rejects files having a `.inc.php` extension
    '*.inc.php',
));
```

### Composing predicates

Predicates are composable using the `IsAnd`, `IsOr` and `IsNot` predicates.

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Predicate\{
    IsAnd,
    IsFunction,
    IsOr
};

// The following filters out:
// * Foo::bar() and Bar::bar() methods (whether static or not)
// * top-level baz() and qux() functions
$trace = Backtrace::capture()->reject(new IsOr(
    new IsAnd(
        fn(Frame $frame) => \in_array($frame->class, ['Foo', 'Bar']), 
        fn(Frame $frame) => $frame->function === 'bar', 
    ),
    new IsFunction('baz', 'qux'),
));
```

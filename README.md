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

The `Backtrace::filter(callable $predicate)` accepts a `callable` and returns a filtered `Backtrace` object.

The `$predicate` argument has the following signature: `callable(Frame, int, Frame[]): bool)`:
it takes a Frame, it's index and the whole frames array and returns a `bool` indicating whether the frame
should be kept or not (semantics are similar to `array_filter()`).

```php
use ju1ius\Footprints\Backtrace;

$trace = Backtrace::capture()
    ->filter(fn(Frame $frame, int $index, array $stack) => $frame->function === 'foo');
```

For convenience, this library comes with a few filtering predicates.

### Ignoring functions

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\IgnoreFunctions;

$trace = Backtrace::capture()->filter(new IgnoreFunctions(
    'foo',
    'Acme\\foobar',
));
```

### Ignoring classes

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\IgnoreClasses;

$trace = Backtrace::capture()->filter(new IgnoreClasses(
    'Foo',
    'Acme\\FooBar',
));
```

### Ignoring class methods

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\IgnoreMethods;

$trace = Backtrace::capture()->filter(new IgnoreMethods(
    // ignores method `bar` of class `Foo`
    'Foo->bar',
    // ignores static method `baz` of class `Acme\FooBar`
    'Acme\\FooBar::baz',
));
```

### Ignoring whole namespaces

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\IgnoreNamespaces;

$trace = Backtrace::capture()->filter(new IgnoreNamespaces(
    // ignores everything in namespace `Acme\Foo` and all it's sub-namespaces.
    'Acme\\Foo',
));
```

### Ignoring files

The `IgnoreFiles` filter accepts glob patterns in the syntax accepted by `fnmatch`.

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\IgnoreFiles;

$trace = Backtrace::capture()->filter(new IgnoreFiles(
    // ignores everything in `/src/foo.php`
    '/src/foo.php',
    // ignores everything in the `/vendor` directory
    '/vendor/*',
    // ignores files having a `.inc.php` extension
    '*.inc.php',
));
```

### Composing predicates

Filter predicates are composable using the `FilterChain` class.

```php
use ju1ius\Footprints\Backtrace;
use ju1ius\Footprints\Filter\FilterChain;
use ju1ius\Footprints\Filter\IgnoreClasses;

$trace = Backtrace::capture()->filter(new FilterChain(
    new IgnoreClasses('Acme\\Foo'),
    fn(Frame $frame) => $frame->function === 'foobar', 
));
```

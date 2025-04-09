<?php declare(strict_types=1);

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Stringable;
use Illuminate\Validation\Rules\Enum;
use Soyhuce\LaravelSafeRequest\Tests\Fixtures\Status;

it('gets form request input as string', function (): void {
    $request = formRequest(['foo' => 'bar', 'baz' => 'qux'], ['foo' => 'string']);

    expect($request)
        ->safeString('foo')->toBe('bar')
        ->safeString('bar')->toBe('')
        ->safeString('bar', 'quia')->toBe('quia')
        ->safeString('baz')->toBe('');
});

it('gets form request input as nullable string', function (): void {
    $request = formRequest(['foo' => 'bar', 'baz' => 'qux'], ['foo' => 'string']);

    expect($request)
        ->safeNullableString('foo')->toBe('bar')
        ->safeNullableString('bar')->toBeNull()
        ->safeNullableString('baz')->toBeNull();
});

it('gets form request input as boolean', function (): void {
    $request = formRequest(['foo' => '0', 'baz' => true], ['foo' => 'boolean']);

    expect($request)
        ->safeBoolean('foo')->toBeFalse()
        ->safeBoolean('bar')->toBeFalse()
        ->safeBoolean('bar', true)->toBeTrue()
        ->safeBoolean('baz')->toBeFalse()
        ->safeBoolean('baz', true)->toBeTrue();
});

it('gets form request input as nullable boolean', function (): void {
    $request = formRequest(['foo' => '0', 'baz' => true], ['foo' => 'boolean']);

    expect($request)
        ->safeNullableBoolean('foo')->toBeFalse()
        ->safeNullableBoolean('bar')->toBeNull()
        ->safeNullableBoolean('baz')->toBeNull();
});

it('gets form request input as integer', function (): void {
    $request = formRequest(['foo' => '12', 'baz' => 14], ['foo' => 'int']);

    expect($request)
        ->safeInteger('foo')->toBe(12)
        ->safeInteger('bar')->toBe(0)
        ->safeInteger('bar', 9)->toBe(9)
        ->safeInteger('baz')->toBe(0)
        ->safeInteger('baz', 9)->toBe(9);
});

it('gets form request input as nullable integer', function (): void {
    $request = formRequest(['foo' => '12', 'baz' => 14], ['foo' => 'int']);

    expect($request)
        ->safeNullableInteger('foo')->toBe(12)
        ->safeNullableInteger('bar')->toBeNull()
        ->safeNullableInteger('baz')->toBeNull();
});

it('gets form request input as float', function (): void {
    $request = formRequest(['foo' => '12.3', 'baz' => 14.5], ['foo' => 'numeric']);

    expect($request)
        ->safeFloat('foo')->toBe(12.3)
        ->safeFloat('bar')->toBe(0.0)
        ->safeFloat('bar', 9.1)->toBe(9.1)
        ->safeFloat('baz')->toBe(0.0)
        ->safeFloat('baz', 9.1)->toBe(9.1);
});

it('gets form request input as nullable float', function (): void {
    $request = formRequest(['foo' => '12.3', 'baz' => 14.5], ['foo' => 'numeric']);

    expect($request)
        ->safeNullableFloat('foo')->toBe(12.3)
        ->safeNullableFloat('bar')->toBeNull()
        ->safeNullableFloat('baz')->toBeNull();
});

it('gets form request input as Stringable', function (): void {
    $request = formRequest(['foo' => 'bar', 'baz' => 'qux'], ['foo' => 'string']);

    expect($request)
        ->safeStr('foo')->toEqual(new Stringable('bar'))
        ->safeStr('bar')->toEqual(new Stringable(''))
        ->safeStr('bar', 'quia')->toEqual(new Stringable('quia'));
});

it('gets form request input as nullable Stringable', function (): void {
    $request = formRequest(['foo' => 'bar', 'baz' => 'qux'], ['foo' => 'string']);

    expect($request)
        ->safeNullableStr('foo')->toEqual(new Stringable('bar'))
        ->safeNullableStr('bar')->toBeNull()
        ->safeNullableStr('baz')->toBeNull();
});

it('gets form request input as datetime', function (): void {
    $request = formRequest(
        [
            'date' => '2022-11-25',
            'datetime' => '2022-11-25 12:00:00',
            'unsafe' => '2022-01-01',
        ],
        [
            'date' => ['string', 'date_format:Y-m-d'],
            'datetime' => ['string', 'date_format:Y-m-d H:i:s'],
        ]
    );

    Date::setTestNow('2023-04-07 00:00:00');

    expect($request)
        ->safeDate('date')->toDateTimeString()->toBe('2022-11-25 00:00:00')
        ->safeDate('datetime')->toDateTimeString()->toBe('2022-11-25 12:00:00')
        ->safeDate('unsafe')->toDateTimeString()->toBe('2023-04-07 00:00:00')
        ->safeDate('foo')->toDateTimeString()->toBe('2023-04-07 00:00:00');
});

it('gets form request input as nullable datetime', function (): void {
    $request = formRequest(
        [
            'date' => '2022-11-25',
            'datetime' => '2022-11-25 12:00:00',
            'unsafe' => '2022-01-01',
        ],
        [
            'date' => ['string', 'date_format:Y-m-d'],
            'datetime' => ['string', 'date_format:Y-m-d H:i:s'],
        ]
    );

    expect($request)
        ->safeNullableDate('date')->toDateTimeString()->toBe('2022-11-25 00:00:00')
        ->safeNullableDate('datetime')->toDateTimeString()->toBe('2022-11-25 12:00:00')
        ->safeNullableDate('unsafe')->toBeNull()
        ->safeNullableDate('foo')->toBeNull();
});

it('gets form request input as enum', function (): void {
    $request = formRequest(
        ['foo' => 'ok', 'bar' => 'ko'],
        ['foo' => new Enum(Status::class)]
    );

    expect($request)
        ->safeEnum('foo', Status::class)->toBe(Status::OK)
        ->safeEnum('bar', Status::class, Status::OK)->toBe(Status::OK);
});

it('gets form request input as nullable enum', function (): void {
    $request = formRequest(
        ['foo' => 'ok', 'bar' => 'ko'],
        ['foo' => new Enum(Status::class)]
    );

    expect($request)
        ->safeNullableEnum('foo', Status::class)->toBe(Status::OK)
        ->safeNullableEnum('bar', Status::class)->toBeNull();
});

it('gets form request input as collection', function (): void {
    $request = formRequest(
        [
            'foo' => [1, 2, 3],
            'bar' => 'ko',
            'baz' => 'foo',
            'toto' => [],
        ],
        [
            'foo' => 'array',
            'foo.*' => 'integer',
            'bar' => 'string',
            'toto' => 'array',
        ]
    );

    expect($request)
        ->safeCollect('foo')->all()->toBe([1, 2, 3])
        ->safeCollect(['bar'])->all()->toBe(['bar' => 'ko'])
        ->safeCollect()->all()->toBe(['bar' => 'ko', 'toto' => [], 'foo' => [1, 2, 3]])
        ->safeCollect(['foo', 'bar'])->all()->toBe(['bar' => 'ko', 'foo' => [1, 2, 3]])
        ->safeCollect('baz')->all()->toBe([])
        ->safeCollect('toto')->all()->toBe([]);
});

it('gets form request input as nullable collection', function (): void {
    $request = formRequest(
        [
            'foo' => [1, 2, 3],
            'bar' => null,
            'baz' => 'foo',
            'toto' => [],
        ],
        [
            'foo' => 'array',
            'foo.*' => 'integer',
            'bar' => 'nullable|string',
            'toto' => 'array',
        ]
    );

    expect($request)
        ->safeNullableCollect('foo')->all()->toBe([1, 2, 3])
        ->safeNullableCollect('bar')->toBeNull()
        ->safeNullableCollect(['bar'])->all()->toBe(['bar' => null])
        ->safeNullableCollect()->all()->toBe(['bar' => null, 'toto' => [], 'foo' => [1, 2, 3]])
        ->safeNullableCollect(['foo', 'bar'])->all()->toBe(['bar' => null, 'foo' => [1, 2, 3]])
        ->safeNullableCollect('baz')->toBeNull()
        ->safeNullableCollect('toto')->all()->toBe([]);
});

it('gets form request input as array', function (): void {
    $request = formRequest(
        [
            'foo' => [1, 2, 3],
            'bar' => 'ko',
            'baz' => 'foo',
            'toto' => [],
        ],
        [
            'foo' => 'array',
            'foo.*' => 'integer',
            'bar' => 'string',
            'toto' => 'array',
        ]
    );

    expect($request)
        ->safeArray('foo')->toBe([1, 2, 3])
        ->safeArray(['bar'])->toBe(['bar' => 'ko'])
        ->safeArray()->toBe(['bar' => 'ko', 'toto' => [], 'foo' => [1, 2, 3]])
        ->safeArray(['foo', 'bar'])->toBe(['bar' => 'ko', 'foo' => [1, 2, 3]])
        ->safeArray('baz')->toBe([])
        ->safeArray('toto')->toBe([]);
});

it('gets form request input as nullable array', function (): void {
    $request = formRequest(
        [
            'foo' => [1, 2, 3],
            'bar' => null,
            'baz' => 'foo',
            'toto' => [],
        ],
        [
            'foo' => 'array',
            'foo.*' => 'integer',
            'bar' => 'nullable|string',
            'toto' => 'array',
        ]
    );

    expect($request)
        ->safeNullableArray('foo')->toBe([1, 2, 3])
        ->safeNullableArray('bar')->toBeNull()
        ->safeNullableArray(['bar'])->toBe(['bar' => null])
        ->safeNullableArray()->toBe(['bar' => null, 'toto' => [], 'foo' => [1, 2, 3]])
        ->safeNullableArray(['foo', 'bar'])->toBe(['bar' => null, 'foo' => [1, 2, 3]])
        ->safeNullableArray('baz')->toBeNull()
        ->safeNullableArray('toto')->toBe([]);
});

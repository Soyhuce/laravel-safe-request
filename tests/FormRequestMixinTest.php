<?php declare(strict_types=1);

use Illuminate\Support\Stringable;
use Illuminate\Validation\Rules\Enum;
use Soyhuce\LaravelSafeRequest\Tests\Fixtures\Status;

it('gets form request input as Stringable', function (): void {
    $request = formRequest(['foo' => 'bar', 'baz' => 'qux'], ['foo' => 'string']);

    expect($request)
        ->safeString('foo')->toEqual(new Stringable('bar'))
        ->safeString('bar')->toEqual(new Stringable(''))
        ->safeString('bar', 'quia')->toEqual(new Stringable('quia'));
});

it('gets form request input as boolean', function (): void {
    $request = formRequest(['foo' => '0', 'baz' => true], ['foo' => 'boolean']);

    expect($request)
        ->safeBoolean('foo')->toBeFalse()
        ->safeBoolean('bar')->toBeFalse()
        ->safeBoolean('bar', true)->toBeTrue();
});

it('gets form request input as integer', function (): void {
    $request = formRequest(['foo' => '12', 'baz' => 14], ['foo' => 'int']);

    expect($request)
        ->safeInteger('foo')->toBe(12)
        ->safeInteger('bar')->toBe(0)
        ->safeInteger('bar', 9)->toBe(9);
});

it('gets form request input as float', function (): void {
    $request = formRequest(['foo' => '12.3', 'baz' => 14.5], ['foo' => 'numeric']);

    expect($request)
        ->safeFloat('foo')->toBe(12.3)
        ->safeFloat('bar')->toBe(0.0)
        ->safeFloat('bar', 9.1)->toBe(9.1);
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

    expect($request)
        ->safeDate('date')->toDateTimeString()->toBe('2022-11-25 00:00:00')
        ->safeDate('datetime')->toDateTimeString()->toBe('2022-11-25 12:00:00')
        ->safeDate('unsafe')->toBeNull();
});

it('gets form request input as enum', function (): void {
    $request = formRequest(
        ['foo' => 'ok', 'bar' => 'ko'],
        ['foo' => new Enum(Status::class)]
    );

    expect($request)
        ->safeEnum('foo', Status::class)->toBe(Status::OK)
        ->safeEnum('bar', Status::class)->toBeNull();
});

it('gets form request input as collection', function (): void {
    $request = formRequest(
        [
            'foo' => [1, 2, 3],
            'bar' => 'ko',
            'baz' => 'foo',
        ],
        [
            'foo' => 'array',
            'foo.*' => 'integer',
            'bar' => 'string',
        ]
    );

    expect($request)
        ->safeCollect('foo')->all()->toBe([1, 2, 3])
        ->safeCollect(['bar'])->all()->toBe(['bar' => 'ko'])
        ->safeCollect()->all()->toBe(['bar' => 'ko', 'foo' => [1, 2, 3]])
        ->safeCollect(['foo', 'bar'])->all()->toBe(['bar' => 'ko', 'foo' => [1, 2, 3]])
        ->safeCollect('baz')->toBeEmpty();
});

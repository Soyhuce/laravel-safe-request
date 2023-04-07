<?php declare(strict_types=1);

/** @var \Illuminate\Foundation\Http\FormRequest $formRequest */

use Soyhuce\LaravelSafeRequest\Tests\Fixtures\Status;
use function PHPStan\Testing\assertType;

assertType('Soyhuce\\LaravelSafeRequest\\Tests\\Fixtures\\Status', $formRequest->safeEnum('foo', Status::class));
assertType('Soyhuce\\LaravelSafeRequest\\Tests\\Fixtures\\Status|null', $formRequest->safeNullableEnum('foo', Status::class));
assertType('Carbon\\CarbonImmutable', $formRequest->safeDate('foo'));
assertType('Carbon\\CarbonImmutable|null', $formRequest->safeNullableDate('foo'));

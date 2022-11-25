<?php declare(strict_types=1);

/** @var \Illuminate\Foundation\Http\FormRequest $formRequest */

use Soyhuce\LaravelSafeRequest\Tests\Fixtures\Status;
use function PHPStan\Testing\assertType;

assertType('Soyhuce\\LaravelSafeRequest\\Tests\\Fixtures\\Status|null', $formRequest->safeEnum('foo', Status::class));
assertType('Carbon\\CarbonImmutable|null', $formRequest->safeDate('foo'));

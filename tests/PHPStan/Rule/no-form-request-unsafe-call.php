<?php declare(strict_types=1);

/** @var \Illuminate\Foundation\Http\FormRequest $formRequest */

use Soyhuce\LaravelSafeRequest\Tests\Fixtures\Status;

$formRequest->string('foo');
$formRequest->boolean('foo');
$formRequest->integer('foo');
$formRequest->float('foo');
$formRequest->date('foo');
$formRequest->enum('foo', Status::class);
$formRequest->collect('foo');

$formRequest->safeString('foo');
$formRequest->safeBoolean('foo');
$formRequest->safeInteger('foo');
$formRequest->safeFloat('foo');
$formRequest->safeDate('foo');
$formRequest->safeEnum('foo', Status::class);
$formRequest->safeCollect('foo');

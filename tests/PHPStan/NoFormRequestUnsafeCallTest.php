<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\Tests\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Soyhuce\LaravelSafeRequest\PHPStan\Rules\NoFormRequestUnsafeCall;

class NoFormRequestUnsafeCallTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoFormRequestUnsafeCall();
    }

    /**
     * @test
     */
    public function rule(): void
    {
        $this->analyse([__DIR__ . '/Rule/no-form-request-unsafe-call.php'], [
            ['Usage of FormRequest::string can be unsafe, prefer using validated data through safeString method.', 7],
            ['Usage of FormRequest::boolean can be unsafe, prefer using validated data through safeBoolean method.', 8],
            ['Usage of FormRequest::integer can be unsafe, prefer using validated data through safeInteger method.', 9],
            ['Usage of FormRequest::float can be unsafe, prefer using validated data through safeFloat method.', 10],
            ['Usage of FormRequest::date can be unsafe, prefer using validated data through safeDate method.', 11],
            ['Usage of FormRequest::enum can be unsafe, prefer using validated data through safeEnum method.', 12],
            ['Usage of FormRequest::collect can be unsafe, prefer using validated data through safeCollect method.', 13],
        ]);
    }
}

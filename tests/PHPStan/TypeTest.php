<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\Tests\PHPStan;

use PHPStan\Testing\TypeInferenceTestCase;

/**
 * @coversNothing
 */
class TypeTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/Type/form-request.php');
    }

    /**
     * @dataProvider dataFileAsserts
     *
     * @test
     */
    public function fileAsserts(string $assertType, string $file, ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/phpstan-tests.neon'];
    }
}

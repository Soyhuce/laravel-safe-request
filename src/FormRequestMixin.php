<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest;

use Closure;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Stringable;
use function is_array;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
class FormRequestMixin
{
    /**
     * Retrieve input from the request as a string.
     */
    public function safeString(): Closure
    {
        return function (string $key, string $default = ''): string {
            return transform(
                $this->validated($key),
                fn (mixed $value) => (string) $value,
                $default
            );
        };
    }

    /**
     * Retrieve input from the request as a nullable string.
     */
    public function safeNullableString(): Closure
    {
        return function (string $key): ?string {
            return transform(
                $this->validated($key),
                fn (mixed $value) => (string) $value
            );
        };
    }

    /**
     * Retrieve input as a boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     */
    public function safeBoolean(): Closure
    {
        return function (string $key, bool $default = false): bool {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default,
                $default
            );
        };
    }

    /**
     * Retrieve input as a nullable boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     */
    public function safeNullableBoolean(): Closure
    {
        return function (string $key): ?bool {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            );
        };
    }

    /**
     * Retrieve input as an integer value.
     */
    public function safeInteger(): Closure
    {
        return function (string $key, int $default = 0): int {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ?? $default,
                $default
            );
        };
    }

    /**
     * Retrieve input as an nullable integer value.
     */
    public function safeNullableInteger(): Closure
    {
        return function (string $key): ?int {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            );
        };
    }

    /**
     * Retrieve input as a float value.
     */
    public function safeFloat(): Closure
    {
        return function (string $key, float $default = 0.0): float {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) ?? $default,
                $default
            );
        };
    }

    /**
     * Retrieve input as a nullable float value.
     */
    public function safeNullableFloat(): Closure
    {
        return function (string $key): ?float {
            return transform(
                $this->validated($key),
                fn (mixed $value) => filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)
            );
        };
    }

    /**
     * Retrieve input from the request as a Stringable.
     */
    public function safeStr(): Closure
    {
        return function (string $key, string $default = ''): Stringable {
            return transform(
                $this->validated($key),
                fn (mixed $value) => str((string) $value),
                str($default)
            );
        };
    }

    /**
     * Retrieve input from the request as a Stringable.
     */
    public function safeNullableStr(): Closure
    {
        return function (string $key): ?Stringable {
            return transform(
                $this->validated($key),
                fn (mixed $value) => str((string) $value)
            );
        };
    }

    /**
     * Retrieve input from the request as a DateTimeInterface instance.
     *
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function safeDate(): Closure
    {
        return function (string $key, ?string $format = null, ?string $tz = null, $default = 'now'): DateTimeInterface {
            return transform(
                $this->validated($key) ?? $default,
                function (mixed $value) use ($format, $tz) {
                    if ($value instanceof DateTimeInterface) {
                        return $value;
                    }

                    if (null === $format) {
                        return Date::parse($value, $tz);
                    }

                    return Date::createFromFormat($format, $value, $tz);
                },
            );
        };
    }

    /**
     * Retrieve input from the request as a nullable DateTimeInterface instance.
     *
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function safeNullableDate(): Closure
    {
        return function (string $key, ?string $format = null, ?string $tz = null): ?DateTimeInterface {
            return transform(
                $this->validated($key),
                function (mixed $value) use ($format, $tz) {
                    if ($value instanceof DateTimeInterface) {
                        return $value;
                    }

                    if (null === $format) {
                        return Date::parse($value, $tz);
                    }

                    return Date::createFromFormat($format, $value, $tz);
                },
            );
        };
    }

    /**
     * Retrieve input from the request as an enum.
     */
    public function safeEnum(): Closure
    {
        return function (string $key, string $enumClass, $default = null): object {
            return transform(
                $this->validated($key) ?? $default,
                function (mixed $value) use ($enumClass) {
                    if ($value instanceof $enumClass) {
                        return $value;
                    }

                    return $enumClass::from($value);
                },
            );
        };
    }

    /**
     * Retrieve input from the request as a nullable enum.
     */
    public function safeNullableEnum(): Closure
    {
        return function (string $key, string $enumClass): ?object {
            return transform(
                $this->validated($key),
                function (mixed $value) use ($enumClass) {
                    if ($value instanceof $enumClass) {
                        return $value;
                    }

                    return $enumClass::tryFrom($value);
                },
            );
        };
    }

    /**
     * Retrieve input from the request as a collection.
     */
    public function safeCollect(): Closure
    {
        return function (array|string|null $key = null): Collection {
            return new Collection(is_array($key) ? Arr::only($this->validated(), $key) : $this->validated($key));
        };
    }
}

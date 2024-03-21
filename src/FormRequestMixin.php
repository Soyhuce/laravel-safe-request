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
        return fn (string $key, string $default = ''): string => transform(
            $this->validated($key),
            fn (mixed $value) => (string) $value,
            $default
        );
    }

    /**
     * Retrieve input from the request as a nullable string.
     */
    public function safeNullableString(): Closure
    {
        return fn (string $key): ?string => transform(
            $this->validated($key),
            fn (mixed $value) => (string) $value
        );
    }

    /**
     * Retrieve input as a boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     */
    public function safeBoolean(): Closure
    {
        return fn (string $key, bool $default = false): bool => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default,
            $default
        );
    }

    /**
     * Retrieve input as a nullable boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     */
    public function safeNullableBoolean(): Closure
    {
        return fn (string $key): ?bool => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        );
    }

    /**
     * Retrieve input as an integer value.
     */
    public function safeInteger(): Closure
    {
        return fn (string $key, int $default = 0): int => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ?? $default,
            $default
        );
    }

    /**
     * Retrieve input as an nullable integer value.
     */
    public function safeNullableInteger(): Closure
    {
        return fn (string $key): ?int => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
        );
    }

    /**
     * Retrieve input as a float value.
     */
    public function safeFloat(): Closure
    {
        return fn (string $key, float $default = 0.0): float => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) ?? $default,
            $default
        );
    }

    /**
     * Retrieve input as a nullable float value.
     */
    public function safeNullableFloat(): Closure
    {
        return fn (string $key): ?float => transform(
            $this->validated($key),
            fn (mixed $value) => filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)
        );
    }

    /**
     * Retrieve input from the request as a Stringable.
     */
    public function safeStr(): Closure
    {
        return fn (string $key, string $default = ''): Stringable => transform(
            $this->validated($key),
            fn (mixed $value) => str((string) $value),
            str($default)
        );
    }

    /**
     * Retrieve input from the request as a Stringable.
     */
    public function safeNullableStr(): Closure
    {
        return fn (string $key): ?Stringable => transform(
            $this->validated($key),
            fn (mixed $value) => str((string) $value)
        );
    }

    /**
     * Retrieve input from the request as a DateTimeInterface instance.
     *
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function safeDate(): Closure
    {
        return fn (string $key, ?string $format = null, ?string $tz = null, $default = 'now'): DateTimeInterface => transform(
            $this->validated($key) ?? $default,
            function (mixed $value) use ($format, $tz) {
                if ($value instanceof DateTimeInterface) {
                    return $value;
                }

                if ($format === null) {
                    return Date::parse($value, $tz);
                }

                return Date::createFromFormat($format, $value, $tz);
            },
        );
    }

    /**
     * Retrieve input from the request as a nullable DateTimeInterface instance.
     *
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function safeNullableDate(): Closure
    {
        return fn (string $key, ?string $format = null, ?string $tz = null): ?DateTimeInterface => transform(
            $this->validated($key),
            function (mixed $value) use ($format, $tz) {
                if ($value instanceof DateTimeInterface) {
                    return $value;
                }

                if ($format === null) {
                    return Date::parse($value, $tz);
                }

                return Date::createFromFormat($format, $value, $tz);
            },
        );
    }

    /**
     * Retrieve input from the request as an enum.
     */
    public function safeEnum(): Closure
    {
        return fn (string $key, string $enumClass, $default = null): object => transform(
            $this->validated($key) ?? $default,
            function (mixed $value) use ($enumClass) {
                if ($value instanceof $enumClass) {
                    return $value;
                }

                return $enumClass::from($value);
            },
        );
    }

    /**
     * Retrieve input from the request as a nullable enum.
     */
    public function safeNullableEnum(): Closure
    {
        return fn (string $key, string $enumClass): ?object => transform(
            $this->validated($key),
            function (mixed $value) use ($enumClass) {
                if ($value instanceof $enumClass) {
                    return $value;
                }

                return $enumClass::tryFrom($value);
            },
        );
    }

    /**
     * Retrieve input from the request as a collection.
     */
    public function safeCollect(): Closure
    {
        return fn (array|string|null $key = null): Collection => new Collection(is_array($key) ? Arr::only($this->validated(), $key) : $this->validated($key));
    }
}

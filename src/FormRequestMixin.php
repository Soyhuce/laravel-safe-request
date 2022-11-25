<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest;

use Closure;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Stringable;
use function function_exists;
use function is_array;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
class FormRequestMixin
{
    /**
     * Retrieve input from the request as a Stringable instance.
     */
    public function safeString(): Closure
    {
        return function (string $key, mixed $default = null): Stringable {
            return str($this->validated($key, $default));
        };
    }

    /**
     * Retrieve input as a boolean value.
     *
     * Returns true when value is "1", "true", "on", and "yes". Otherwise, returns false.
     */
    public function safeBoolean(): Closure
    {
        return function (?string $key = null, bool $default = false): bool {
            return filter_var($this->validated($key, $default), FILTER_VALIDATE_BOOLEAN);
        };
    }

    /**
     * Retrieve input as an integer value.
     */
    public function safeInteger(): Closure
    {
        return function (string $key, int $default = 0): int {
            return (int) $this->validated($key, $default);
        };
    }

    /**
     * Retrieve input as a float value.
     */
    public function safeFloat(): Closure
    {
        return function (string $key, float $default = 0.0): float {
            return (float) $this->input($key, $default);
        };
    }

    /**
     * Retrieve input from the request as a Carbon instance.
     *
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function safeDate(): Closure
    {
        return function (string $key, ?string $format = null, ?string $tz = null): ?DateTimeInterface {
            if ($this->validated($key) === null || $this->isNotFilled($key)) {
                return null;
            }

            if (null === $format) {
                return Date::parse($this->input($key), $tz);
            }

            return Date::createFromFormat($format, $this->input($key), $tz);
        };
    }

    /**
     * Retrieve input from the request as an enum.
     */
    public function safeEnum(): Closure
    {
        return function (string $key, string $enumClass) {
            if ($this->validated($key) === null
                || $this->isNotFilled($key)
                || !function_exists('enum_exists')
                || !enum_exists($enumClass)
                || !method_exists($enumClass, 'tryFrom')) {
                return null;
            }

            return $enumClass::tryFrom($this->input($key));
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

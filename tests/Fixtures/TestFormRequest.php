<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\Tests\Fixtures;

use Illuminate\Foundation\Http\FormRequest;

class TestFormRequest extends FormRequest
{
    /** @var array<string, mixed> */
    private array $rules;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @param array<string, mixed> $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
}

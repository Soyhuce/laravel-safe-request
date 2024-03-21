<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\PHPStan\Rules;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use function in_array;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
class NoFormRequestUnsafeCall implements Rule
{
    /** @var array<int, string> */
    protected array $methods = [
        'string',
        'boolean',
        'integer',
        'float',
        'date',
        'enum',
        'collect',
    ];

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Identifier) {
            return [];
        }

        if (!(new ObjectType(FormRequest::class))->isSuperTypeOf($scope->getType($node->var))->yes()) {
            return [];
        }

        $name = $node->name;
        if (in_array($name->toLowerString(), $this->methods, true)) {
            return [$this->formatError($name->toString())];
        }

        return [];
    }

    private function formatError(string $method): string
    {
        $safeMethod = 'safe' . Str::ucfirst($method);
        $safeNullableMethod = 'safeNullable' . Str::ucfirst($method);

        return "Usage of FormRequest::{$method} can be unsafe, prefer using validated data through {$safeMethod} or {$safeNullableMethod} methods.";
    }
}

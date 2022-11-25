<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\PHPStan\ReturnTypes;

use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class FormRequestSafeEnumReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return FormRequest::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'safeEnum';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): Type {
        $expr = $methodCall->getArgs()[1]->value;
        if ($expr instanceof ClassConstFetch && $expr->class instanceof FullyQualified) {
            return TypeCombinator::addNull(new ObjectType($expr->class->toString()));
        }

        return new NeverType();
    }
}

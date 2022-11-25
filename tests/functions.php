<?php declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Soyhuce\LaravelSafeRequest\Tests\Fixtures\TestFormRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * @param array<array-key, mixed> $data
 * @param array<string, mixed> $rules
 */
function formRequest(array $data, array $rules): FormRequest
{
    $symfonyRequest = SymfonyRequest::create('/', 'POST', $data);

    $formRequest = FormRequest::createFrom(
        Request::createFromBase($symfonyRequest),
        new TestFormRequest()
    )
        ->setContainer(app());

    $route = new Route('POST', '/', fn () => null);
    $route->parameters = [];
    $formRequest->setRouteResolver(fn () => $route);

    $formRequest->setRules($rules);
    $formRequest->validateResolved();

    return $formRequest;
}

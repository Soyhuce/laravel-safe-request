<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest\Tests\Fixtures;

enum Status: string
{
    case OK = 'ok';
    case KO = 'ko';
}

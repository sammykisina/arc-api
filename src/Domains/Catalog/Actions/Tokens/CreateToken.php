<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Tokens;

use Domains\Catalog\Models\Token;
use Domains\Catalog\ValueObjects\TokenValueObject;

class CreateToken {
    public static function handle(TokenValueObject $tokenValueObject): ?Token {
        return Token::create(attributes:  $tokenValueObject->toArray());
    }
}

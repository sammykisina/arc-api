<?php

declare(strict_types=1);

use Domains\Catalog\Models\Token;

dataset(
    name: 'token',
    dataset: [
        fn () => Token::factory()->create()
    ]
);

dataset(
    name: 'token_not_tied_to_item',
    dataset: [
        fn () => Token::factory()->not_tied_to_item()->create()
    ]
);

dataset(
    name: 'admin_token_not_tied_to_item',
    dataset: [
        fn () => Token::factory()->belonging_to_admin()->not_tied_to_item()->create()
    ]
);

dataset(
    name: 'approved_token',
    dataset: [
        fn () => Token::factory()->approved()->create()
    ]
);

dataset(
    name: 'approved_admin_token_not_tired_to_item',
    dataset: [
        fn () => Token::factory()->belonging_to_admin()->approved()->create()
    ]
);

<?php
// app/Config/rules.php
declare(strict_types=1);

return [
    'rules' => [
        \App\Rules\SelectStarRule::class,
        \App\Rules\MissingWhereClauseRule::class,
        \App\Rules\NoIndexRule::class,
        \App\Rules\LimitOffsetRule::class,
    ]
];
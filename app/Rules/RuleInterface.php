<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

interface RuleInterface
{
//    public function getId(): string;
//
//
//    public function matches(string $sql, array $context = []): bool;
//
//    public function getMessage(): string;
//
//    public function getExplanation(): string;

    public function check(array $tokens): array;
    public function getSeverity(): string;
}

<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

class NoIndexRule implements RuleInterface
{
    private const SEVERITY = Severity::WARNING;

    public function check(array $tokens): array
    {
        $issues = [];
        $table = null;
        $hasWhere = false;
        $hasIndexHint = false;

        foreach ($tokens as $i => $token) {
            if ($token === 'from' && isset($tokens[$i + 1])) {
                $table = $tokens[$i + 1];
            }
            if ($token === 'where') {
                $hasWhere = true;
            }
            if (in_array($token, ['use', 'force', 'ignore']) &&
                isset($tokens[$i + 1]) &&
                $tokens[$i + 1] === 'index') {
                $hasIndexHint = true;
                break;
            }
        }

        if ($table && $hasWhere && !$hasIndexHint) {
            $issues[] = [
                'message' => "Query on table '{$table}' does not use an index. Consider adding appropriate indexes.",
                'severity' => self::SEVERITY
            ];
        }

        return $issues;
    }

    public function getSeverity(): string
    {
        return self::SEVERITY->value;
    }
}
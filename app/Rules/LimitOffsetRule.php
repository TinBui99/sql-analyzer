<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

class LimitOffsetRule implements RuleInterface
{
    private const SEVERITY = Severity::WARNING;

    public function check(array $tokens): array
    {
        $issues = [];
        $hasLimit = false;
        $hasOffset = false;
        $hasOrderBy = false;

        foreach ($tokens as $i => $token) {
            if ($token === 'limit') {
                $hasLimit = true;
                // Check if there's an offset
                if (isset($tokens[$i + 2]) && is_numeric($tokens[$i + 1]) && $tokens[$i + 2] === ',') {
                    $hasOffset = true;
                }
            }
            if ($token === 'order' && isset($tokens[$i + 1]) && $tokens[$i + 1] === 'by') {
                $hasOrderBy = true;
            }
        }

        if ($hasLimit && !$hasOrderBy) {
            $issues[] = [
                'message' => 'Using LIMIT without ORDER BY can return inconsistent results',
                'severity' =>  $this->getSeverity()
            ];
        }

        if ($hasOffset && !$hasOrderBy) {
            $issues[] = [
                'message' => 'Using OFFSET without ORDER BY can lead to unpredictable results',
                'severity' =>  $this->getSeverity()
            ];
        }

        return $issues;
    }

    public function getSeverity(): string
    {
        return self::SEVERITY->value;
    }
}
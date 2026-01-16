<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

class MissingWhereClauseRule implements RuleInterface
{
    private const SEVERITY = Severity::WARNING;

    public function check(array $tokens): array
    {
        $issues = [];
        $hasWhere = false;
        $hasUpdateOrDelete = false;
        $hasSelect = false;

        foreach ($tokens as $i => $token) {
            if ($token === 'update' || $token === 'delete') {
                $hasUpdateOrDelete = true;
            }
            if ($token === 'select') {
                $hasSelect = true;
            }
            if ($token === 'where') {
                $hasWhere = true;
                break;
            }
            // Stop checking if we hit these clauses without finding WHERE
            if (in_array($token, ['group', 'order', 'limit', 'join', 'having'])) {
                break;
            }
        }

        if (($hasUpdateOrDelete || $hasSelect) && !$hasWhere) {
            $issues[] = [
                'message' => 'Query is missing a WHERE clause which may cause full table scan',
                'severity' => $this->getSeverity()
            ];
        }

        return $issues;
    }

    public function getSeverity(): string
    {
        return self::SEVERITY->value;
    }
}
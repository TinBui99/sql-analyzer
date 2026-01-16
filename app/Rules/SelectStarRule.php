<?php
declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

class SelectStarRule implements RuleInterface
{
    public function check(array $tokens): array
    {
        $issues = [];

        if (in_array('select', $tokens, true)) {
            $selectIndex = array_search('select', $tokens, true);

//            var_dump($tokens);
//            var_dump($selectIndex);
//            var_dump(isset($tokens[$selectIndex + 1]));
//            var_dump($tokens[$selectIndex + 1]);die();
            if (isset($tokens[$selectIndex + 1]) && $tokens[$selectIndex + 1] === '*') {
                $issues[] = [
                    'message' => 'Avoid using SELECT * as it can cause performance issues',
                    'severity' => $this->getSeverity()
                ];
            }
        }

        return $issues;
    }

    public function getSeverity(): string
    {
        return Severity::WARNING->value;
    }
}
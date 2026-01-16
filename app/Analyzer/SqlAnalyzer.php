<?php
declare(strict_types=1);

namespace App\Analyzer;

use App\Rules\RuleInterface;

class SqlAnalyzer
{
    private array $rules = [];
    private array $enabledRules = [];
    private array $availableRules = [
        \App\Rules\SelectStarRule::class,
        \App\Rules\MissingWhereClauseRule::class,
        \App\Rules\NoIndexRule::class,
        \App\Rules\LimitOffsetRule::class,
    ];

    public function __construct()
    {
        $this->loadRules();
    }
    
    /**
     * Set which rules should be enabled
     * 
     * @param array $enabledRules Array of rule class names to enable
     */
    public function setEnabledRules(array $enabledRules): void
    {
        $this->enabledRules = $enabledRules;
        $this->loadRules(); // Reload rules with new configuration
    }

    public function analyze(string $sql): array
    {
        $tokens = $this->getTokens($sql);
        return $this->checkForIssues($tokens);
    }

    public function getTokens(string $sql): array
    {
        // Simple tokenizer - can be enhanced with a proper SQL parser
        $sql = strtolower(trim($sql));
        return array_values(array_filter(preg_split('/\s+/', $sql)));
    }

    public function checkForIssues(array $tokens): array
    {
        $result = [
            'issue' => [],
            'warning' => [],
            'error' => []
        ];

//        var_dump($this->rules);
//        die();
        foreach ($this->rules as $rule) {
            $issues = $rule->check($tokens);
//            var_dump($issues);die();
            if (is_array($issues)) {
                foreach ($issues as $issue) {
                    if (is_array($issue) && isset($issue['message'])) {
                        $severity = $issue['severity'] ?? 'warning';
                        // Ensure severity is a valid key
                        if (in_array($severity, ['issue', 'warning', 'error'])) {
                            $result[$severity][] = $issue['message'];
                        } else {
                            // Default to 'warning' if severity is invalid
                            $result['warning'][] = $issue['message'];
                        }
                    } else if (is_string($issue)) {
                        $result['warning'][] = $issue;
                    }
                }
            }
        }
//        var_dump($result);
//        die();

        return $result;
    }

    private function loadRules(): void
    {
        $this->rules = [];
        
        foreach ($this->availableRules as $ruleClass) {
            // If no specific rules are enabled, enable all by default
            // Otherwise, only enable the specified rules
            $ruleName = substr(strrchr($ruleClass, '\\'), 1); // Get just the class name
            
            if (empty($this->enabledRules) || in_array($ruleName, $this->enabledRules)) {
                $this->rules[] = new $ruleClass();
            }
        }
    }
}
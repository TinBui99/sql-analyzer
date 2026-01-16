<?php

declare(strict_types=1);

namespace App\Engine;

use App\Rules\RuleInterface;
use App\Rules\AbstractRule;

class RuleFactory
{
    /**
     * @var array<class-string<AbstractRule>, array{enabled: bool, severity: string}>
     */
    private array $rulesConfig;

    /**
     * @param array<class-string<AbstractRule>, array{enabled: bool, severity?: string}> $rulesConfig
     */
    public function __construct(array $rulesConfig = [])
    {
        $this->rulesConfig = $rulesConfig;
    }

    /**
     * @return array<RuleInterface>
     */
    public function createEnabledRules(): array
    {
        $enabledRules = [];

        foreach ($this->rulesConfig as $ruleClass => $config) {
            if ($config['enabled'] === false) {
                continue;
            }

            if (!class_exists($ruleClass)) {
                throw new \RuntimeException(sprintf('Rule class %s does not exist', $ruleClass));
            }

            if (!is_subclass_of($ruleClass, AbstractRule::class)) {
                throw new \LogicException(sprintf('Rule class %s must extend %s', $ruleClass, AbstractRule::class));
            }

            /** @var AbstractRule $rule */
            $rule = new $ruleClass();
            
            // Override severity if specified in config
            if (isset($config['severity'])) {
                $reflection = new \ReflectionProperty($rule, 'severity');
                $reflection->setAccessible(true);
                $reflection->setValue($rule, \App\ValueObject\Severity::from($config['severity']));
            }

            $enabledRules[] = $rule;
        }

        return $enabledRules;
    }
}

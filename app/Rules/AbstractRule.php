<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObject\Severity;

abstract class AbstractRule implements RuleInterface
{
    protected const PATTERN = '';
    
    protected Severity $severity;
    protected string $message;
    protected string $explanation;

    public function __construct()
    {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function getId(): string
    {
        return static::class;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    protected function matchesPattern(string $sql): bool
    {
        if (static::PATTERN === '') {
            throw new \LogicException(sprintf('PATTERN constant must be defined in %s', static::class));
        }
        
        return (bool) preg_match(static::PATTERN, $this->normalizeSql($sql));
    }

    protected function normalizeSql(string $sql): string
    {
        // Convert to single line and trim
        $sql = trim(preg_replace('/\s+/', ' ', $sql));
        
        // Remove extra spaces around parentheses and commas
        $sql = preg_replace('/\s*\(\s*/', '(', $sql);
        $sql = preg_replace('/\s*\)/', ')', $sql);
        $sql = preg_replace('/\s*,\s*/', ',', $sql);
        
        // Convert to lowercase for case-insensitive matching
        return strtolower($sql);
    }
}

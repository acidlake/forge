<?php

namespace Base\Tools;

use Base\Interfaces\UuidStrategyInterface;
use Exception;

class UuidManager
{
    private array $strategies = [];
    private string $defaultStrategy;

    public function __construct()
    {
        // Register built-in strategies
        $this->register("uuidv4", new \Base\UuidStrategies\UuidV4Strategy());
        $this->register("uuidv2", new \Base\UuidStrategies\UuidV2Strategy());

        // Set default strategy
        $this->defaultStrategy = "uuidv4";
    }

    /**
     * Register a new UUID strategy.
     *
     * @param string $name
     * @param UuidStrategyInterface $strategy
     */
    public function register(
        string $name,
        UuidStrategyInterface $strategy
    ): void {
        $this->strategies[$name] = $strategy;
    }

    /**
     * Set the default UUID strategy.
     *
     * @param string $name
     * @throws Exception
     */
    public function setDefaultStrategy(string $name): void
    {
        if (!isset($this->strategies[$name])) {
            throw new Exception("Strategy {$name} is not registered.");
        }

        $this->defaultStrategy = $name;
    }

    /**
     * Generate a UUID using a specific strategy.
     *
     * @param string|null $strategyName
     * @return string
     * @throws Exception
     */
    public function generate(string $strategyName = null): string
    {
        $strategyName = $strategyName ?? $this->defaultStrategy;

        if (!isset($this->strategies[$strategyName])) {
            throw new Exception("Strategy {$strategyName} is not registered.");
        }

        return $this->strategies[$strategyName]->generate();
    }
}

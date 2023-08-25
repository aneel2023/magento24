<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Rule\Design;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\AbstractCallableNode;
use PHPMD\Rule\FunctionAware;
use PHPMD\Rule\MethodAware;

/**
 * Checks the number of arguments for the given function or method
 * node against a configured threshold.
 */
class LongParameterList extends AbstractRule implements MethodAware, FunctionAware
{
    private ?array $excludedMethods = null;
    private ?array $includedMethods = null;

    public function apply(AbstractNode $node): void
    {
        if (!$node instanceof AbstractCallableNode) {
            return;
        }

        $method = $node->getName();
        if (in_array($method, $this->getExcludedMethods())) {
            return;
        }

        $onlyMethods = $this->getIncludedMethods();
        if ($onlyMethods && !in_array($method, $onlyMethods)) {
            return;
        }

        $threshold = $this->getIntProperty('minimum');

        $count = $node->getParameterCount();
        if ($count <= $threshold) {
            return;
        }

        $this->addViolation(
            $node,
            [
                $node->getType(),
                $method,
                $count,
                $threshold,
            ]
        );
    }

    private function getIncludedMethods(): array
    {
        if ($this->includedMethods !== null) {
            return $this->includedMethods;
        }

        $this->includedMethods = array_filter(
            array_map('trim', explode(',', $this->getStringProperty('only-methods', ''))),
            static fn (string $value) => $value !== ''
        );

        return $this->includedMethods;
    }

    private function getExcludedMethods(): array
    {
        if ($this->excludedMethods !== null) {
            return $this->excludedMethods;
        }

        $this->excludedMethods = array_filter(
            array_map('trim', explode(',', $this->getStringProperty('except-methods', ''))),
            static fn (string $value) => $value !== ''
        );

        return $this->excludedMethods;
    }
}

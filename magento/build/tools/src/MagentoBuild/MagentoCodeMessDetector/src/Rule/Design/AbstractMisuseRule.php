<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Rule\Design;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use MagentoBuild\MagentoCodeMessDetector\Exception\NonPresentationClassException;
use MagentoBuild\MagentoCodeMessDetector\Validator\IsPresentationLayerClassValidator;
use Throwable;

abstract class AbstractMisuseRule extends AbstractRule implements ClassAware
{
    /**
     * @throws ReflectionException
     */
    abstract protected function isRestrictedParameterClass(ReflectionClass $class): bool;

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function apply(AbstractNode $node): void
    {
        try {
            /** @phpstan-ignore-next-line */
            $class = new ReflectionClass($node->getFullQualifiedName());
        } catch (Throwable $exception) {
            //Failed to load class, nothing we can do
            return;
        }

        if (!$this->doesUseRestrictedClasses($class)) {
            return;
        }

        try {
            $classValidator = new IsPresentationLayerClassValidator();
            $classValidator->validate($class);

            return;
        } catch (NonPresentationClassException $e) {
            $this->addViolation($node, [$node->getFullQualifiedName()]);
        }
    }

    protected function doesMethodHasRestrictedClass(ReflectionMethod $method): bool
    {
        foreach ($method->getParameters() as $argument) {
            try {
                $parameterClass = $this->getParameterClass($argument);
                if ($parameterClass === null) {
                    continue;
                }

                if ($this->isRestrictedParameterClass($parameterClass)) {
                    return true;
                }
            } catch (ReflectionException) {
                //Failed to load the argument's class information
                continue;
            }
        }

        return false;
    }

    /**
     * Whether given class depends on classes to pay attention to.
     */
    protected function doesUseRestrictedClasses(ReflectionClass $class): bool
    {
        $constructor = $class->getConstructor();
        if ($constructor) {
            return $this->doesMethodHasRestrictedClass($constructor);
        }

        return false;
    }

    /**
     * Get class by reflection parameter
     */
    protected function getParameterClass(ReflectionParameter $reflectionParameter): ?ReflectionClass
    {
        /** @var ReflectionNamedType|null $parameterType */
        $parameterType = $reflectionParameter->getType();
        // In PHP8, $parameterType could be an instance of ReflectionUnionType, which doesn't have isBuiltin method.
        if ($parameterType !== null && method_exists($parameterType, 'isBuiltin') === false) {
            return null;
        }

        return $parameterType && !$parameterType->isBuiltin()
            /** @phpstan-ignore-next-line */
            ? new ReflectionClass($parameterType->getName())
            : null;
    }
}

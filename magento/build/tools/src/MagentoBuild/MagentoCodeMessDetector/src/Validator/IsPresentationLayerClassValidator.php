<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Validator;

use MagentoBuild\Checkout\Block\Checkout\LayoutProcessorInterface;
use MagentoBuild\Framework\App\ActionInterface;
use MagentoBuild\Framework\View\Element\Block\ArgumentInterface;
use MagentoBuild\Framework\View\Element\BlockInterface;
use MagentoBuild\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use MagentoBuild\Framework\View\Element\UiComponent\DataProvider\Document;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use MagentoBuild\MagentoCodeMessDetector\Exception\NonPresentationClassException;
use Throwable;

class IsPresentationLayerClassValidator
{
    public function validate(\ReflectionClass $class): bool
    {
        if (
            $this->isController($class)
            || $this->isBlock($class)
            || $this->isUiDataProvider($class)
            || $this->isUiDocument($class)
            || $this->isControllerPlugin($class)
            || $this->isBlockPlugin($class)
            || $this->isLayoutProcessor($class)
            || $this->isViewModel($class)
            || $this->isActionDTO($class)
        ) {
            return true;
        }

        throw new NonPresentationClassException(
            "Class {$class->getName()} is not part of the presentation layer"
        );
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

    /**
     * Is class implements action interface approach used on TV project.
     *
     * This type of actions, used to pass as parameters to services and might have access to
     * * request
     * * session
     * * cookies
     */
    private function isActionDTO(ReflectionClass $class): bool
    {
        $interfaces = $class->getInterfaceNames();
        foreach ($interfaces as $interface) {
            if (str_ends_with($interface, 'ActionInterface')) {
                return true;
            }
        }

        return false;
    }

    private function isController(ReflectionClass $class): bool
    {
        return $class->implementsInterface(ActionInterface::class);
    }

    private function isBlock(ReflectionClass $class): bool
    {
        return $class->implementsInterface(BlockInterface::class);
    }

    private function isUiDataProvider(ReflectionClass $class): bool
    {
        return $class->implementsInterface(
            DataProviderInterface::class
        );
    }

    /**
     * Is given class a Layout Processor?
     */
    private function isLayoutProcessor(ReflectionClass $class): bool
    {
        // check whether class is used as layout processor for checkout
        if ($class->implementsInterface(LayoutProcessorInterface::class)) {
            return true;
        }

        // there are some custom layout processors used in different places, no interfaces are implemented for them.
        // This kind of layout processors have to have LayoutProcessor suffix.
        return str_ends_with($class->getName(), 'LayoutProcessor');
    }

    private function isViewModel(ReflectionClass $class): bool
    {
        return $class->implementsInterface(
            ArgumentInterface::class
        );
    }

    /**
     * Is given class an HTML UI Document?
     */
    private function isUiDocument(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Document::class)
            || $class->getName() === Document::class;
    }

    private function isControllerPlugin(ReflectionClass $class): bool
    {
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!preg_match('/^(after|around|before).+/i', $method->getName())) {
                continue;
            }

            try {
                $parameters = $method->getParameters();
                if (count($parameters) === 0) {
                    continue;
                }
                $argument = $this->getParameterClass($parameters[0]);
            } catch (Throwable $exception) {
                //Non-existing class (autogenerated perhaps) or doesn't have an argument.
                continue;
            }
            if (!$argument) {
                continue;
            }

            $isAction = $argument->implementsInterface(ActionInterface::class)
                || $argument->getName() === ActionInterface::class;
            if ($isAction) {
                return true;
            }
        }

        return false;
    }

    private function isBlockPlugin(ReflectionClass $class): bool
    {
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!preg_match('/^(after|around|before).+/i', $method->getName())) {
                continue;
            }

            try {
                $parameters = $method->getParameters();
                if (count($parameters) === 0) {
                    continue;
                }
                $argument = $this->getParameterClass($parameters[0]);
            } catch (Throwable $exception) {
                //Non-existing class (autogenerated perhaps) or doesn't have an argument.
                continue;
            }
            if (!$argument) {
                continue;
            }

            $isBlock = $argument->implementsInterface(BlockInterface::class)
                || $argument->getName() === BlockInterface::class;
            if ($isBlock) {
                return true;
            }
        }

        return false;
    }
}

<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Rule\Design;

use MagentoBuild\Framework\App\RequestInterface;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;
use ReflectionClass;
use MagentoBuild\MagentoCodeMessDetector\Exception\NonPresentationClassException;
use MagentoBuild\MagentoCodeMessDetector\Validator\IsPresentationLayerClassValidator;

class RequestMisuse extends AbstractMisuseRule implements MethodAware
{
    private const TYPE_CLASS = 'class';

    public function apply(AbstractNode $node): void
    {
        if ($node->getType() === self::TYPE_CLASS) {
            parent::apply($node);

            return;
        }

        if (!($node instanceof MethodNode)) {
            return;
        }

        try {
            $className = "{$node->getNamespaceName()}\\{$node->getParentName()}";
            /** @phpstan-ignore-next-line */
            $class = new ReflectionClass($className);
            $method = $class->getMethod($node->getName());
        } catch (\Throwable $exception) {
            //Failed to load class, nothing we can do
            return;
        }

        if (!$this->doesMethodHasRestrictedClass($method)) {
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

    protected function isRestrictedParameterClass(ReflectionClass $class): bool
    {
        return $class->getName() === RequestInterface::class
            || $class->implementsInterface(RequestInterface::class);
    }
}

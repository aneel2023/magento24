<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Rule\Design;

use MagentoBuild\Framework\Session\SessionManagerInterface;
use MagentoBuild\Framework\Stdlib\Cookie\CookieReaderInterface;
use ReflectionClass;

class CookieAndSessionMisuse extends AbstractMisuseRule
{
    protected function isRestrictedParameterClass(ReflectionClass $class): bool
    {
        return $class->implementsInterface(SessionManagerInterface::class)
            || $class->getName() === SessionManagerInterface::class
            // Covers wrappers on some Session Managers, e.g. Checkout/Customer session
            || str_contains($class->getName(), 'Session')
            || $class->implementsInterface(CookieReaderInterface::class)
            || $class->getName() === CookieReaderInterface::class
            // Covers wrappers on Cookie Managers
            || str_contains($class->getName(), 'Cookie');
    }
}

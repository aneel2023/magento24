<?php
declare(strict_types=1);

namespace MagentoBuild\MagentoCodeMessDetector\Rule\Design;

use MagentoBuild\Framework\App\ActionInterface;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

/**
 * Actions must process a defined list of HTTP methods.
 */
class AllPurposeAction extends AbstractRule implements ClassAware
{
    public function apply(AbstractNode $node): void
    {
        // Skip validation for Abstract Controllers
        /** @phpstan-ignore-next-line */
        if ($node->isAbstract()) {
            return;
        }
        try {
            if (!class_exists($node->getFullQualifiedName(), true)) {
                return;
            }
            $impl = class_implements($node->getFullQualifiedName(), true);
        } catch (\Throwable $exception) {
            //Couldn't load a class.
            return;
        }

        if (!is_array($impl) || !in_array(ActionInterface::class, $impl, true)) {
            return;
        }

        $methodsDefined = false;
        foreach ($impl as $i) {
            if (preg_match('/\\\Http[a-z]+ActionInterface$/i', $i)) {
                $methodsDefined = true;
                break;
            }
        }
        if ($methodsDefined) {
            return;
        }

        $this->addViolation($node, [$node->getFullQualifiedName()]);
    }
}

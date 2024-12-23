<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\ObjectManager;

class AttributeProvider
{
    public function getAttributeLabel(string $fieldName): string
    {
        return (string)$this->getAttribute($fieldName)->getFrontendLabel();
    }

    public function getAttribute(string $fieldName): AbstractAttribute
    {
        if (empty($fieldName)) {
            throw new \RuntimeException('No field name defined for class "' . get_class($this) . '"');
        }

        $eavConfig = ObjectManager::getInstance()->get(EavConfig::class);
        $attribute = $eavConfig->getAttribute('customer_address', $fieldName);
        if ($attribute instanceof AbstractAttribute) {
            return $attribute;
        }

        throw new \RuntimeException('Unable to load attribute "' . $fieldName . '"');
    }
}


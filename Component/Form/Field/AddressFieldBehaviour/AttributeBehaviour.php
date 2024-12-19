<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\ObjectManager;

trait AttributeBehaviour
{
    /**
     * @deprecated: Use getFieldName() instead
     */
    public function getAttributeCode(): string
    {
        return $this->getFieldName();
    }

    public function getAttributeLabel(): string
    {
        return (string)$this->getAttribute()->getFrontendLabel();
    }

    protected function getAttribute(): AbstractAttribute
    {
        $fieldName = $this->getFieldName();
        if (empty($fieldName)) {
            throw new \RuntimeException('No field name defined for class "'.get_class($this).'"');
        }

        $eavConfig = ObjectManager::getInstance()->get(EavConfig::class);
        $attribute = $eavConfig->getAttribute('customer_address', $this->getFieldName());
        if ($attribute instanceof AbstractAttribute) {
            return $attribute;
        }

        throw new \RuntimeException('Unable to load attribute "'.$fieldName.'"');
    }
}

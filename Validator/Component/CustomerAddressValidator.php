<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Model\Address\CompositeValidator;
use Magento\Customer\Model\AddressFactory as CustomerAddressFactory;
use Magento\Eav\Model\Validator\Attribute\Backend as EavValidator;
use Magento\Framework\Validator\Factory as ValidatorFactory;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;
use Yireo\LokiFieldComponents\Component\Base\Field\FieldComponent;

class CustomerAddressValidator implements ValidatorInterface
{
    public function __construct(
        private CustomerAddressFactory $customerAddressFactory,
        private EavValidator $eavValidator,
        private CompositeValidator $compositeValidator,
        private ValidatorFactory $validatorFactory
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        if (false === $component instanceof FieldComponent) {
            return true;
        }

        if (empty($value)) {
            return true;
        }

        $customerAddress = $this->customerAddressFactory->create();
        $customerAddress->addData($this->getDefaultData());
        $customerAddress->setData($component->getViewModel()->getFieldName(), $value);

        $validator = $this->validatorFactory->createValidator('customer_address', 'save');
        $result = $validator->isValid($customerAddress);

        if ($result === false) {
            return $validator->getMessages();
        }

        return true;
    }

    private function getDefaultData(): array
    {
        return [
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'street' => ['Amalialaan 126B'],
            'city' => 'Baarn',
            'postcode' => '3743KJ',
            'country_id' => 'NL',
            'telephone' => '0123456789',
        ];
    }
}

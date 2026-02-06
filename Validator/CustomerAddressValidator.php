<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Framework\Validator\Factory as ValidatorFactory;
use Magento\Customer\Model\AddressFactory;

class CustomerAddressValidator
{
    public function __construct(
        private readonly AddressMapper $addressMapper,
        private AddressFactory $addressFactory,
        private ValidatorFactory $validatorFactory,
    ) {
    }

    /**
     * @return string[]|true
     */
    public function validate(AddressInterface $address)
    {
        /** @var Address $addressModel */
        $addressModel = $this->addressFactory->create();
        $addressData = $this->addressMapper->toFlatArray($address);
        $addressModel->addData($addressData);

        $validator = $this->validatorFactory->createValidator('customer_address', 'save');
        if (!$validator->isValid($address)) {

            $result = [];
            foreach ($validator->getMessages() as $message) {
                if (is_string($message)) {
                    $result[] = $message;
                }

                if (is_array($message)) {
                    foreach ($message as $m) {
                        $result[] = $m;
                    }
                }
            }

            return $result;
        }

        $result = $addressModel->validate();
        if ($result !== true) {
            return $result;
        }

        return true;
    }
}

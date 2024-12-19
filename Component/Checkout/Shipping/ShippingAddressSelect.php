<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Yireo\LokiCheckout\Component\Form\Field\Field;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Magento\Customer\Model\Address\Mapper as AddressMapper;

class ShippingAddressSelect extends Field
{
    use StepBehaviour;

    public array $shippingAddresses = [];

    public function __construct(
        private CustomerAddressRepository $customerAddressRepository,
        private AddressConfig $addressConfig,
        private AddressMapper $addressMapper,
        private CheckoutSession $checkoutSession,
        private CustomerProvider $customerProvider
    ) {
    }

    public function initialize(): void
    {
        $this->shippingAddresses = $this->customerProvider->getAddresses();

        $quote = $this->checkoutSession->getQuote();
        $this->value = (string)$quote->getShippingAddress()->getCustomerAddressId();

        if (!$this->value) {
            $this->value = $this->customerProvider->getDefaultShippingAddressId();
        }

        if (!empty($this->value)) {
            $this->save((string)$this->value);
        }
    }

    public function isDisabled(): bool
    {
        return (bool)$this->checkoutSession->getNewAddress();
    }

    public function validate(): bool
    {
        if (false === (bool)$this->customerProvider->isLoggedIn()) {
            return false;
        }

        if (empty($this->value)) {
            return false;
        }

        return parent::validate();
    }

    public function getFieldLabel(): string
    {
        return 'Select your Shipping Address';
    }

    public function save($value): void
    {
        $customerShippingAddress = $this->customerAddressRepository->getById((int)$value);
        $quoteShippingAddress = $this->checkoutSession->getQuote()->getShippingAddress();
        $quoteShippingAddress->importCustomerAddressData($customerShippingAddress);
        $quoteShippingAddress->setCustomerAddressId($customerShippingAddress->getId());
        $quoteShippingAddress->save();
    }

    public function isAllowRendering(): bool
    {
        if (false === $this->customerProvider->isLoggedIn()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}

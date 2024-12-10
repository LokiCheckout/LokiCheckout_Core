<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Magento\Customer\Model\Address\Mapper as AddressMapper;

class ShippingAddressSelect extends FieldComponent
{
    use AlpineDataBehaviour;
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

    public function boot(): void
    {
        if (false === $this->customerProvider->isLoggedIn()) {
            $this->allowRendering = false;
            $this->valid = false;

            return;
        }

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

    public function getAdditionalAlpineData(): array
    {
        return [
            'disabled' => $this->isDisabled(),
        ];
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

    protected function afterSave($value): void
    {
        $this->emit('afterSaveShippingAddress');
        parent::afterSave($value);
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
}

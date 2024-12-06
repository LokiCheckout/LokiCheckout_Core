<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Magento\Customer\Model\Address\Mapper as AddressMapper;

class ShippingAddress extends GenericComponent
{
    use AlpineDataBehaviour;
    use StepBehaviour;

    public array $shippingAddresses = [];
    public int|string $currentShippingAddress = 0;
    public bool $newAddress = false;

    public function __construct(
        private CheckoutState $checkoutState,
        private CustomerSession $customerSession,
        private CustomerRepositoryInterface $customerRepository,
        private CustomerAddressRepository $customerAddressRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private AddressConfig $addressConfig,
        private AddressMapper $addressMapper,
        private CheckoutSession $checkoutSession,
        private CustomerProvider $customerProvider,
    ) {
    }

    public function boot()
    {
        if ($this->customerProvider->isLoggedIn()) {
            $this->bootForCustomer();
        }

        $this->newAddress = $this->loadNewAddress();
    }

    private function bootForCustomer()
    {
        $quote = $this->checkoutState->getQuote();
        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());

        $this->shippingAddresses = $this->customerProvider->getAddresses();

        $this->currentShippingAddress = (int)$quote->getShippingAddress()->getCustomerAddressId();
        if (!$this->currentShippingAddress) {
            $this->currentShippingAddress = (int)$customer->getDefaultShipping();
        }

        if (!empty($this->currentShippingAddress)) {
            $this->saveCustomerAddressId((int)$this->currentShippingAddress);
        }
    }

    public function isEnabled(): bool
    {
        return false === (bool)$this->checkoutState->getQuote()->isVirtual();
    }

    private function loadNewAddress(): bool
    {
        if (false === $this->customerProvider->isLoggedIn()) {
            return true;
        }

        if (count($this->shippingAddresses) < 1) {
            return true;
        }

        return (bool)$this->checkoutSession->getNewAddress();
    }

    public function setNewAddress($newAddress): void
    {
        $newAddress = (bool)$newAddress;
        $this->checkoutSession->setNewAddress($newAddress);
        $this->newAddress = $newAddress;

        if (false === $this->newAddress) {
            return;
        }

        if (false === $this->customerSession->isLoggedIn()) {
            return;
        }

        $quoteShippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        $quoteShippingAddress->setCustomerAddressId(0);
        $quoteShippingAddress->save();
        $this->emit('afterSaveShippingAddress');
    }

    public function getAdditionalAlpineData(): array
    {
        return [
            'newAddress' => $this->newAddress ? true : false,
            'buttonLabelSelectAddress' => $this->getButtonLabelSelectAddress(),
            'buttonLabelNewAddress' => $this->getButtonLabelNewAddress(),
        ];
    }

    public function getButtonLabelNewAddress(): string
    {
        return (string)__('Add new shipping address');
    }

    public function getButtonLabelSelectAddress(): string
    {
        return (string)__('Select existing address from list');
    }
}

<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Loki\Field\Util\Option;
use Loki\Field\Util\OptionFactory;
use LokiCheckout\Core\Validator\CustomerAddressValidator;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\Manager as MessageManager;
use Magento\Framework\UrlFactory;

class CustomerProvider
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
        private readonly OptionFactory $optionFactory,
        private MessageManager $messageManager,
        private CustomerAddressValidator $customerAddressValidator,
        private UrlFactory $urlFactory
    ) {
    }

    public function isLoggedIn(): bool
    {
        return $this->checkoutState->getCustomerSession()->isLoggedIn();
    }

    /**
     * @return AddressInterface[]
     * @throws LocalizedException
     */
    public function getAddresses(): array
    {
        if (false === $this->isLoggedIn()) {
            return [];
        }

        static $addresses = null;
        if ($addresses !== null) {
            return $addresses;
        }

        $customer = $this->getCustomer();
        $this->searchCriteriaBuilder->addFilter('parent_id', $customer->getId());
        $searchResults = $this->customerAddressRepository->getList($this->searchCriteriaBuilder->create());

        $addresses = $searchResults->getItems();
        return $addresses;
    }

    /**
     * @return Option[]
     * @throws LocalizedException
     */
    public function getAddressLines(): array
    {
        static $addressLines = null;
        if ($addressLines !== null) {
            return $addressLines;
        }

        $addressLines = [];
        foreach ($this->getAddresses() as $address) {
            $validationResult = $this->customerAddressValidator->validate($address);

            $addressLines[] = $this->optionFactory->create(
                (int)$address->getId(),
                $this->getAddressInline($address),
                $validationResult !== true
            );

            if (is_array($validationResult)) {
                $url = $this->urlFactory->create()->getUrl('customer/address/edit', ['id' => $address->getId()]);
                $this->messageManager->addComplexErrorMessage(
                    'invalid_customer_address',
                    [
                        'address_url' => $url
                    ]
                );
            }
        }

        return $addressLines;
    }

    public function hasInvalidAddressLines(): bool
    {
        foreach ($this->getAddressLines() as $addressLine) {
            if ($addressLine->isDisabled()) {
                return true;
            }
        }

        return false;
    }

    public function getDefaultShippingAddressId(): string
    {
        $customer = $this->getCustomer();

        return (string)$customer->getDefaultShipping();
    }

    public function getDefaultBillingAddressId(): string
    {
        $customer = $this->getCustomer();

        return (string)$customer->getDefaultBilling();
    }

    private function getCustomer(): CustomerInterface
    {
        $customerId = $this->checkoutState->getCustomerSession()->getCustomerId();
        return $this->customerRepository->getById((int)$customerId);
    }

    private function getAddressInline(AddressInterface $address): string
    {
        $addressData = $this->addressMapper->toFlatArray($address);
        $format = $this->addressConfig->getFormatByCode(AddressConfig::DEFAULT_ADDRESS_FORMAT);
        return $format->getData('renderer')->renderArray($addressData);
    }
}

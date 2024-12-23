<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;

use Yireo\LokiCheckout\Component\Form\Field\FieldViewModel;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ViewModel extends FieldViewModel
{
    use StepBehaviour;

    private array $shippingMethods = [];

    public function __construct(
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly CartRepositoryInterface $cartRepository
    ) {
    }

    public function initialize(): void
    {
        if ($this->isLazyload()) {
            return;
        }

        $shippingAddress = $this->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        foreach ($this->shippingMethodManagement->getList($quote->getId()) as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $this->shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        $this->value = (string)$shippingAddress->getShippingMethod();
        if (false === array_key_exists($this->value, $this->shippingMethods)) {
            $this->value = '';
        }

        if (empty($this->value) && $this->lokiCheckoutConfig->selectDefaultShippingMethod()) {
            // @todo: Make this optional
            $firstShippingMethod = (string)array_key_first($this->shippingMethods);
            $this->value = $firstShippingMethod;
            $this->shippingAddressState->getShippingAddress()->setShippingMethod($firstShippingMethod);
            $this->cartRepository->save($quote);
        }

        if (!empty($this->value)) {
            $this->valid = true;
        }

        parent::initialize();
    }

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    public function save($value): void
    {
        $this->saveShippingMethodToQuote($value);

    }

    private function saveShippingMethodToQuote(string $shippingMethodCode): void
    {
        $shippingAddress = $this->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethodCode);

        $this->cartRepository->save($quote);
    }

    public function getFieldLabel(): string
    {
        return 'Shipping Methods';
    }

    public function isRequired(): bool
    {
        return true;
    }
}

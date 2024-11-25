<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\JsNameBehaviour;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectConfig;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingMethods extends Component
{
    use InjectConfig;
    use StepBehaviour;
    use JsNameBehaviour;

    private array $shippingMethods = [];

    public string $value = '';

    public bool $valid = false;

    public function __construct(
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly CartRepositoryInterface $cartRepository
    ) {
    }

    public function boot()
    {
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

        if (empty($this->value) && $this->config->selectDefaultShippingMethod()) {
            // @todo: Make this optional
            $firstShippingMethod = (string)array_key_first($this->shippingMethods);
            $this->value = $firstShippingMethod;
            $this->shippingAddressState->getShippingAddress()->setShippingMethod($firstShippingMethod);
            $this->cartRepository->save($quote);
        }

        if (!empty($this->value)) {
            $this->valid = true;
        }
    }

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    public function setValue(string $value)
    {
        $this->saveShippingMethodToQuote($value);
        $this->value = $value;
        $this->valid = true;
        $this->emit('shippingStepSave');

        return $value;
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
}

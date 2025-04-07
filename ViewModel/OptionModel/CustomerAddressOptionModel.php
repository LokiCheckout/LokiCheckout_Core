<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Component\Checkout\Address\Select\SelectViewModel;
use Yireo\LokiFieldComponents\Util\OptionModelInterface;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

class CustomerAddressOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly CustomerSession $customerSession
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return [];
        }

        $block = $viewModel->getBlock();
        $showEmptySelectOption = (bool)$block->getShowEmptySelectOption(); // @doc
        $emptySelectOptionLabel = (string)$block->getEmptySelectOptionLabel(); // @doc

        /** @var SelectViewModel $viewModel */
        $options = [];
        if ($showEmptySelectOption) {
            $options[0] = $emptySelectOptionLabel;
        }

        foreach ($viewModel->getAddressLines() as $addressId => $addressLine) {
            $options[$addressId] = $addressLine;
        }

        return $options;
    }
}

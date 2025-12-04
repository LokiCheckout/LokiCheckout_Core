<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\State;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Address;

interface AddressStateInterface extends ArgumentInterface
{
    public function get(string $attributeCode): mixed;

    public function set(string $attributeCode, string $value): void;

    public function getAddress(): Address;
}

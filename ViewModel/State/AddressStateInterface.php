<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Framework\View\Element\Block\ArgumentInterface;

interface AddressStateInterface extends ArgumentInterface
{
    public function load(string $attributeCode):mixed;
    public function save(string $attributeCode, string $value);
}

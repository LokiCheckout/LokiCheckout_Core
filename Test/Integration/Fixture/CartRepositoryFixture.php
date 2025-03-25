<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;

class CartRepositoryFixture implements CartRepositoryInterface
{
    private ?Quote $quote = null;

    private function getQuote(): Quote
    {
        if ($this->quote instanceof Quote) {
            return $this->quote;
        }

        $this->quote = ObjectManager::getInstance()->create(Quote::class);
        return $this->quote;
    }

    public function get($cartId)
    {
        return $this->getQuote();
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
    }

    public function getForCustomer($customerId, array $sharedStoreIds = [])
    {
        return $this->getQuote();
    }

    public function getActive($cartId, array $sharedStoreIds = [])
    {
        return $this->getQuote();
    }

    public function getActiveForCustomer($customerId, array $sharedStoreIds = [])
    {
        return $this->getQuote();
    }

    public function save(CartInterface $quote)
    {
        return true;
    }

    public function delete(CartInterface $quote)
    {
        return true;
    }
}

<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Model\Quote;

class CartRepositoryStub implements CartRepositoryInterface
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

    public function generateQuote(array $data = []): void
    {
        $this->quote = ObjectManager::getInstance()->create(Quote::class);
        $this->quote->setData($data);

        if (isset($data['shipping_address'])) {
            $this->quote->getShippingAddress()->setData($data['shipping_address']);
        }

        if (isset($data['billing_address'])) {
            $this->quote->getBillingAddress()->setData($data['billing_address']);
        }
    }

    public function setQuote(Quote $quote): void
    {
        $this->quote = $quote;
    }

    public function get($cartId)
    {
        return $this->getQuote();
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var CartSearchResultsInterface $fake */
        $fake = null;
        return $fake;
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

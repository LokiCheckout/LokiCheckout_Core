<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Repository;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Website;

class NewsletterRepository
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    public function hasCustomerIdRecord(int $customerId): bool
    {
        $select = $this->getCustomerIdSelect($customerId);
        return (bool)($this->getConnection()->fetchAll($select) > 0);
    }

    public function isCustomerIdSubscribed(int $customerId): bool
    {
        $select = $this->getCustomerIdSelect($customerId);
        return (bool)$this->getConnection()->fetchOne($select);
    }

    public function hasGuestEmailRecord(string $email): bool
    {
        $select = $this->getGuestEmailSelect($email);
        return (bool)($this->getConnection()->fetchAll($select) > 0);
    }

    public function isGuestEmailSubscribed(string $email): bool
    {
        if ($email === '' || $email === '0') {
            return false;
        }

        $select = $this->getGuestEmailSelect($email);
        return (bool)$this->getConnection()->fetchOne($select);
    }

    private function getCustomerIdSelect(int $customerId): Select
    {
        return $this->getConnection()
            ->select()
            ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
            ->where('store_id IN (?)', $this->getStoreIds())
            ->where('customer_id = '.$customerId)
            ->where('subscriber_status = 1')
            ->limit(1);
    }

    private function getGuestEmailSelect(string $email): Select
    {
        return $this->getConnection()
            ->select()
            ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
            ->where('subscriber_email = ?', $email)
            ->where('store_id IN (?)', $this->getStoreIds())
            ->where('customer_id = 0')
            ->where('subscriber_status = 1')
            ->limit(1);
    }

    /**
     * @return int[]
     * @throws LocalizedException
     */
    private function getStoreIds(): array
    {
        /** @var Website $website */
        $website = $this->storeManager->getWebsite();
        return $website->getStoreIds();
    }

    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
}

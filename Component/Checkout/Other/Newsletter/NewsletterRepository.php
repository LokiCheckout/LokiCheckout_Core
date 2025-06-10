<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Magento\Newsletter\Model\Subscriber;
use Magento\Store\Model\Website;
use Yireo\LokiFieldComponents\Component\Base\Field\FieldRepository;

/**
 * @method NewsletterContext getContext()
 */
class NewsletterRepository extends FieldRepository
{
    public function getValue(): mixed
    {
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if ($email === '' || $email === '0') {
            return false;
        }

        if ($this->getContext()->getCustomerSession()->isLoggedIn()) {
            return $this->isCustomerSubscribed();
        }

        return $this->isGuestSubscribed($email);
    }

    public function saveValue(mixed $value): void
    {
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if ((bool)$value) {
            $this->subscribeEmail($email);
            $this->getGlobalMessageRegistry()->addNotice('You have been subscribed to the newsletter');

            return;
        }

        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->load($email, 'subscriber_email'); // @phpstan-ignore-line
        if (!$subscriber->getId() > 0) {
            return;
        }

        if ($this->getContext()->getCustomerSession()->isLoggedIn()) {
            $this->unsubscriberCustomer();

            return;
        }

        $this->unsubscribeEmail($email, $subscriber);
        $this->getGlobalMessageRegistry()->addNotice('You have been unsubscribed from the newsletter');
    }

    private function subscribeEmail(string $email)
    {
        $this->getContext()->getSubscriptionManager()->subscribe($email, $this->getStoreId());
    }

    private function unsubscribeEmail(string $email, Subscriber $subscriber): void
    {
        $confirmCode = $subscriber->getSubscriberConfirmCode();
        $this->getContext()->getSubscriptionManager()->unsubscribe($email, $this->getStoreId(), $confirmCode);
    }

    private function unsubscriberCustomer()
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        $this->getContext()->getSubscriptionManager()->unsubscribeCustomer($customerId, $this->getStoreId());
    }

    private function getStoreId(): int
    {
        return (int)$this->getContext()->getCheckoutState()->getQuote()->getStoreId();
    }

    private function isCustomerSubscribed(): bool
    {
        /** @var Website $website */
        $website = $this->getContext()->getStoreManager()->getWebsite();
        $storeIds = $website->getStoreIds();
        $connection = $this->getContext()->getResourceConnection()->getConnection();
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        $select = $connection
            ->select()
            ->from($this->getContext()->getResourceConnection()->getTableName('newsletter_subscriber'))
            ->where('store_id IN (?)', $storeIds)
            ->where('customer_id = ' . $customerId)
            ->where('subscriber_status = 1')
            ->limit(1);

        return (bool)$connection->fetchOne($select);
    }

    private function isGuestSubscribed(string $email): bool
    {
        if ($email === '' || $email === '0') {
            return false;
        }

        /** @var Website $website */
        $website = $this->getContext()->getStoreManager()->getWebsite();
        $storeIds = $website->getStoreIds();
        $connection = $this->getContext()->getResourceConnection()->getConnection();
        $select = $connection
            ->select()
            ->from($this->getContext()->getResourceConnection()->getTableName('newsletter_subscriber'))
            ->where('subscriber_email = ?', $email)
            ->where('store_id IN (?)', $storeIds)
            ->where('customer_id = 0')
            ->where('subscriber_status = 1')
            ->limit(1);

        return (bool)$connection->fetchOne($select);
    }
}

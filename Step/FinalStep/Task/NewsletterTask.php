<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Task;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Repository\NewsletterRepository;
use LokiCheckout\Core\Util\NewsletterChoice;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Quote\Model\Quote;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * When the newsletter checkbox is checked by default and the customer did not uncheck it, the
 * subscription is created once the order is actually placed (instead of when rendering the page).
 */
class NewsletterTask implements TaskInterface
{
    private ?string $email = null;
    private int $customerId = 0;
    private int $storeId = 0;

    public function __construct(
        private readonly Config $config,
        private readonly NewsletterChoice $newsletterChoice,
        private readonly NewsletterRepository $newsletterRepository,
        private readonly SubscriptionManagerInterface $subscriptionManager,
        private readonly CustomerSession $customerSession,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function beforePlaceOrder(Quote $quote): void
    {
        $this->email = null;
        $this->customerId = 0;

        if (false === $this->config->isNewsletterCheckedByDefault()) {
            return;
        }

        $email = (string)$quote->getCustomerEmail();
        if ($this->newsletterChoice->getChoice($email) !== null) {
            return;
        }

        $this->email = $email;
        $this->customerId = $this->customerSession->isLoggedIn() ? (int)$this->customerSession->getCustomerId() : 0;
        $this->storeId = (int)$quote->getStoreId();
    }

    public function afterPlaceOrder(int $orderId): void
    {
        $email = $this->email;
        $this->email = null;

        if ($email === null || ($email === '' && $this->customerId === 0)) {
            return;
        }

        try {
            if ($this->customerId > 0) {
                if (false === $this->newsletterRepository->hasCustomerIdRecord($this->customerId)) {
                    $this->subscriptionManager->subscribeCustomer($this->customerId, $this->storeId);
                }

                return;
            }

            if (false === $this->newsletterRepository->hasGuestEmailRecord($email)) {
                $this->subscriptionManager->subscribe($email, $this->storeId);
            }
        } catch (Throwable $exception) {
            $this->logger->error('LokiCheckout newsletter subscription failed: ' . $exception->getMessage());
        }
    }
}

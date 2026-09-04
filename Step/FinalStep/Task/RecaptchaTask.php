<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Task;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Recaptcha\Config;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Guard;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\Quote\Model\Quote;

/**
 * Final gate for the place order challenge. The token itself is verified by the reCAPTCHA
 * component earlier in the checkout; this task makes sure the order cannot be placed without
 * that verification, also when the finalize URL is called directly.
 */
class RecaptchaTask implements TaskInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly Guard $guard,
        private readonly ManagerInterface $messageManager,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    /**
     * @throws RedirectException
     */
    public function beforePlaceOrder(Quote $quote): void
    {
        if ($this->guard->assertPassed(FormId::PLACE_ORDER)) {
            return;
        }

        $this->messageManager->addErrorMessage($this->config->getValidationFailureMessage());

        throw (new RedirectException())->setUrl($this->urlFactory->create()->getUrl('checkout'));
    }

    public function afterPlaceOrder(int $orderId): void
    {
    }
}

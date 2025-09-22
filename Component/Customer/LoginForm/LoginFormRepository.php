<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Customer\LoginForm;

use Loki\Components\Component\ComponentRepository;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\Manager;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

/**
 * @method CheckoutContext getContext()
 */
class LoginFormRepository extends ComponentRepository
{
    public function __construct(
        private readonly AccountManagementInterface $accountManagement,
        private readonly CustomerSession $customerSession,
        private readonly PhpCookieManager $cookieManager,
        private readonly CookieMetadataFactory $cookieMetadataFactory,
        private readonly Manager $messageManager,
        private readonly CustomerUrl $customerUrl,
    ) {
    }

    public function getValue(): mixed
    {
        return $this->customerSession->isLoggedIn();
    }

    public function saveValue(mixed $value): void
    {
        if (false === is_array($value)) {
            return;
        }

        if (!isset($value['email']) || !isset($value['password'])) {
            return;
        }

        try {
            $customer = $this->accountManagement->authenticate($value['email'], $value['password']);

            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            $this->getGlobalMessageRegistry()->addSuccess('You have logged in'); // @todo: Check right translation

            if ($this->cookieManager->getCookie('mage-cache-sessid')) {
                $metadata = $this->cookieMetadataFactory->createCookieMetadata();
                $metadata->setPath('/');
                $this->cookieManager->deleteCookie('mage-cache-sessid', $metadata);
            }
        } catch (EmailNotConfirmedException $e) {
            $this->messageManager->addComplexErrorMessage(
                'confirmAccountErrorMessage',
                ['url' => $this->customerUrl->getEmailConfirmationUrl($value['username'])]
            );

        } catch (AuthenticationException $e) {
            $this->messageManager->addErrorMessage(__(
                'The account sign-in was incorrect or your account is disabled temporarily. '
                . 'Please wait and try again later.'
            ));

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An unspecified error occurred. Please contact us for assistance.')
            );
        }

        // @todo: Prevent reloading all targets in case of an error
    }
}

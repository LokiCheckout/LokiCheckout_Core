<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\Manager as MessageManager;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

class LoginCustomer
{
    public function __construct(
        private readonly AccountManagementInterface $accountManagement,
        private readonly PhpCookieManager $cookieManager,
        private readonly CookieMetadataFactory $cookieMetadataFactory,
        private readonly MessageManager $messageManager,
        private readonly CustomerUrl $customerUrl,
        private readonly CustomerSession $customerSession,
    ) {
    }

    public function execute(string $email, string $password): void
    {
        try {
            $customer = $this->accountManagement->authenticate($email, $password);

            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            $this->messageManager->addSuccessMessage('You have logged in'); // @todo: Check right translation

            if ($this->cookieManager->getCookie('mage-cache-sessid')) {
                $metadata = $this->cookieMetadataFactory->createCookieMetadata();
                $metadata->setPath('/');
                $this->cookieManager->deleteCookie('mage-cache-sessid', $metadata);
            }
        } catch (EmailNotConfirmedException $e) {
            $this->messageManager->addComplexErrorMessage(
                'confirmAccountErrorMessage',
                ['url' => $this->customerUrl->getEmailConfirmationUrl($email)]
            );

        } catch (AuthenticationException $e) {
            $this->messageManager->addErrorMessage(__(
                'The account sign-in was incorrect or your account is disabled temporarily. '
                . 'Please wait and try again later: '.$e->getMessage()
            ));

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An unspecified error occurred. Please contact us for assistance.')
            );
        }
    }
}

<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Payment\Vault;

use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Api\PaymentTokenRepositoryInterface;

class PaymentTokenProvider
{
    public function __construct(
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly PaymentTokenRepositoryInterface $paymentTokenRepository,
        private readonly SerializerInterface $serializer,
        private readonly Session $customerSession,
        private readonly NormalizedPaymentTokenFactory $normalizedPaymentTokenFactory
    ) {
    }

    public function getByHash(string $publicHash): NormalizedPaymentToken
    {
        if (!$this->customerSession->isLoggedIn()) {
            throw new NotFoundException(__('No payment token for guests'));
        }

        $this->searchCriteriaBuilder->addFilter(PaymentTokenInterface::IS_VISIBLE, 1);
        $this->searchCriteriaBuilder->addFilter(PaymentTokenInterface::IS_ACTIVE, 1);
        $this->searchCriteriaBuilder->addFilter(PaymentTokenInterface::PUBLIC_HASH, $publicHash);

        $this->searchCriteriaBuilder->addFilter(
            PaymentTokenInterface::CUSTOMER_ID,
            $this->customerSession->getCustomerId()
        );

        $searchResults = $this->paymentTokenRepository->getList($this->searchCriteriaBuilder->create());
        $items = $searchResults->getItems();

        if (empty($items)) {
            throw new NotFoundException(__('No payment token for guests'));
        }

        $item = array_shift($items);
        return $this->normalizedPaymentTokenFactory->create($item);
    }

    /**
     * @return array{
     *      array{
     *          "public_hash": string,
     *          "type": string,
     *          "name": string,
     *          "maskedCC": string,
     *     }
     * }
     */
    /**
     * @param string $paymentMethodCode
     * @return PaymentTokenInterface[]
     */
    public function getAll(string $paymentMethodCode = ''): array
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }

        $this->searchCriteriaBuilder->addFilter(PaymentTokenInterface::IS_VISIBLE, 1);
        $this->searchCriteriaBuilder->addFilter(PaymentTokenInterface::IS_ACTIVE, 1);
        $this->searchCriteriaBuilder->addFilter(
            PaymentTokenInterface::CUSTOMER_ID,
            $this->customerSession->getCustomerId()
        );

        if ($paymentMethodCode !== '' && $paymentMethodCode !== '0') {
            $this->searchCriteriaBuilder->addFilter(
                PaymentTokenInterface::PAYMENT_METHOD_CODE,
                $paymentMethodCode
            );
        }

        return $this->paymentTokenRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }

    /**
     * @return NormalizedPaymentToken[]
     * @throws NotFoundException
     */
    public function getAllNormalized(string $paymentMethodCode = ''): array
    {
        $paymentTokens = $this->getAll($paymentMethodCode);
        $normalizedPaymentTokens = [];

        foreach ($paymentTokens as $paymentToken) {
            $normalizedPaymentTokens[] = $this->normalizedPaymentTokenFactory->create($paymentToken);
        }

        return $normalizedPaymentTokens;
    }
}

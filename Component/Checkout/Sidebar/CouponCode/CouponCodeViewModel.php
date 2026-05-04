<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\CouponCode;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\SalesRule\Model\Rule;

/**
 * @method CheckoutContext getContext()
 * @method CouponCodeRepository getRepository()
 */
class CouponCodeViewModel extends CheckoutViewModel
{
    public function __construct(
        private readonly CollectionFactory $ruleCollectionFactory
    ) {
    }

    protected bool $skipValidation = true;

    public function isAllowRendering(): bool
    {
        if (false === $this->hasActiveCouponRules()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function getFieldLabel(): string
    {
        return 'Coupon code';
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutCouponCodeComponent';
    }

    private function hasActiveCouponRules(): bool
    {
        $today = (new \DateTime())->format('Y-m-d');

        $collection = $this->ruleCollectionFactory->create();

        $collection->addFieldToFilter('is_active', 1);

        $collection->addFieldToFilter(
            ['from_date', 'from_date'],
            [
                ['null' => true],
                ['lteq' => $today],
            ]
        );

        $collection->addFieldToFilter(
            ['to_date', 'to_date'],
            [
                ['null' => true],
                ['gteq' => $today],
            ]
        );

        $collection->addFieldToFilter('coupon_type', [
            'in' => [
                Rule::COUPON_TYPE_SPECIFIC,
                Rule::COUPON_TYPE_AUTO,
            ],
        ]);

        $collection->setPageSize(1);

        return (bool) $collection->getSize();
    }
}

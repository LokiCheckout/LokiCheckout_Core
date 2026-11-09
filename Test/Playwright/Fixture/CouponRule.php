<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Playwright\Fixture;

use Loki\FunctionalTests\Service\ConfigureAction\ConfigureActionInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\SalesRule\Model\ResourceModel\Rule as RuleResource;
use Magento\SalesRule\Model\ResourceModel\Coupon\CollectionFactory;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Store\Model\StoreManagerInterface;

class CouponRule implements ConfigureActionInterface
{
    public const REQUEST_KEY = 'coupon_rule';

    public function __construct(
        private readonly RuleFactory $ruleFactory,
        private readonly RuleResource $ruleResource,
        private readonly CollectionFactory $collectionFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
    ) {
    }

    public function execute(array $requestData): array
    {
        if (empty($requestData[self::REQUEST_KEY]) || !is_array($requestData[self::REQUEST_KEY])) {
            return [];
        }

        $couponCode = trim((string)($requestData[self::REQUEST_KEY]['code'] ?? ''));
        if ($couponCode === '') {
            return [self::REQUEST_KEY => ['errors' => ['Coupon rule requires a "code"']]];
        }

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('code', $couponCode);
        if ($collection->getSize() > 0) {
            return [self::REQUEST_KEY => ['created' => false]];
        }

        $rule = $this->ruleFactory->create();
        $rule->setName('Loki Playwright coupon '.$couponCode);
        $rule->setIsActive(1);
        $rule->setCouponType(Rule::COUPON_TYPE_SPECIFIC);
        $rule->setCouponCode($couponCode);
        $rule->setSimpleAction(Rule::BY_PERCENT_ACTION);
        $rule->setDiscountAmount((float)($requestData[self::REQUEST_KEY]['discount'] ?? 10));
        $rule->setStopRulesProcessing(0);
        $rule->setWebsiteIds($this->getWebsiteIds());
        $rule->setCustomerGroupIds($this->getCustomerGroupIds());
        $this->ruleResource->save($rule);

        return [self::REQUEST_KEY => ['created' => true]];
    }

    /**
     * @return int[]
     */
    private function getWebsiteIds(): array
    {
        $websiteIds = [];
        foreach ($this->storeManager->getWebsites() as $website) {
            $websiteIds[] = (int)$website->getId();
        }

        return $websiteIds;
    }

    /**
     * @return int[]
     */
    private function getCustomerGroupIds(): array
    {
        $groupIds = [];
        foreach ($this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems() as $group) {
            $groupIds[] = (int)$group->getId();
        }

        return $groupIds;
    }
}

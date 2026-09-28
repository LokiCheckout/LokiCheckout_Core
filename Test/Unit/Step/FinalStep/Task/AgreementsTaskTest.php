<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Step\FinalStep\Task;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Step\FinalStep\Task\AgreementsTask;
use LokiCheckout\Core\Test\Unit\Stub\CheckoutSessionDataStub;
use Magento\Checkout\Api\AgreementsValidatorInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Url;
use Magento\Framework\UrlFactory;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;

class AgreementsTaskTest extends TestCase
{
    public function testBeforePlaceOrderPassesWhenAgreementsAreValid(): void
    {
        $messageManager = $this->createMock(ManagerInterface::class);
        $messageManager->expects($this->never())->method('addErrorMessage');

        $task = $this->createTask(['1', '2'], ['1', '2'], $messageManager);
        $task->beforePlaceOrder($this->createMock(Quote::class));
    }

    public function testBeforePlaceOrderRedirectsWhenAgreementsAreInvalid(): void
    {
        $messageManager = $this->createMock(ManagerInterface::class);
        $messageManager->expects($this->once())->method('addErrorMessage');

        $task = $this->createTask([], ['1'], $messageManager);

        try {
            $task->beforePlaceOrder($this->createMock(Quote::class));
            $this->fail('Expected a RedirectException');
        } catch (RedirectException $exception) {
            $this->assertSame('https://example.com/checkout/', $exception->getUrl());
        }
    }

    public function testBeforePlaceOrderTreatsMissingSessionDataAsNoAgreements(): void
    {
        $validator = $this->createMock(AgreementsValidatorInterface::class);
        $validator->expects($this->once())->method('isValid')->with([])->willReturn(true);

        $task = new AgreementsTask(
            $validator,
            CheckoutSessionDataStub::create(),
            $this->createMock(ManagerInterface::class),
            $this->createMock(UrlFactory::class)
        );

        $task->beforePlaceOrder($this->createMock(Quote::class));
    }

    private function createTask(
        array $savedAgreementIds,
        array $requiredAgreementIds,
        ManagerInterface $messageManager
    ): AgreementsTask {
        $validator = $this->createMock(AgreementsValidatorInterface::class);
        $validator->method('isValid')->willReturnCallback(
            fn (array $agreementIds) => array_diff($requiredAgreementIds, $agreementIds) === []
        );

        $session = CheckoutSessionDataStub::create();
        $session->setData('agreement_ids', $savedAgreementIds);

        $url = $this->createMock(Url::class);
        $url->method('getUrl')->with('checkout')->willReturn('https://example.com/checkout/');
        $urlFactory = $this->createMock(UrlFactory::class);
        $urlFactory->method('create')->willReturn($url);

        return new AgreementsTask($validator, $session, $messageManager, $urlFactory);
    }
}

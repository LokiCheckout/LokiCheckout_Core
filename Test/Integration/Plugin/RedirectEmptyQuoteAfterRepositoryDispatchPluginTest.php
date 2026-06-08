<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Exception\RedirectException;
use Loki\Components\Util\Controller\RepositoryDispatcher;
use LokiCheckout\Core\Plugin\RedirectEmptyQuoteAfterRepositoryDispatchPlugin;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[AppArea('frontend')]
class RedirectEmptyQuoteAfterRepositoryDispatchPluginTest extends TestCase
{
    private RepositoryDispatcher $dispatcher;
    private ComponentInterface $component;

    protected function setUp(): void
    {
        $this->dispatcher = ObjectManager::getInstance()->get(RepositoryDispatcher::class);
        $this->component = $this->createMock(ComponentInterface::class);
    }

    public function testSuccessfulDispatchDoesNotThrow(): void
    {
        $subject = $this->createPlugin([LocalizedException::class]);

        $called = false;
        $proceed = function () use (&$called): void {
            $called = true;
        };

        $subject->aroundDispatch($this->dispatcher, $proceed, $this->component, []);
        $this->assertTrue($called);
    }

    public function testAllowedExceptionBecomesRedirect(): void
    {
        $subject = $this->createPlugin([LocalizedException::class]);

        $proceed = function (): void {
            throw new LocalizedException(__('Quote is empty'));
        };

        $this->expectException(RedirectException::class);
        $subject->aroundDispatch($this->dispatcher, $proceed, $this->component, []);
    }

    public function testNonAllowedExceptionIsRethrown(): void
    {
        $subject = $this->createPlugin([LocalizedException::class]);

        $proceed = function (): void {
            throw new RuntimeException('Different error');
        };

        $this->expectException(RuntimeException::class);
        $subject->aroundDispatch($this->dispatcher, $proceed, $this->component, []);
    }

    private function createPlugin(array $allowedExceptionClasses): RedirectEmptyQuoteAfterRepositoryDispatchPlugin
    {
        return new RedirectEmptyQuoteAfterRepositoryDispatchPlugin(
            ObjectManager::getInstance()->get(UrlFactory::class),
            ObjectManager::getInstance()->get(ManagerInterface::class),
            $allowedExceptionClasses
        );
    }
}

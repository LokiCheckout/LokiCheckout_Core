<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Controller\Index;

use LokiCheckout\Core\Controller\Index\Finalize;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[AppArea('frontend')]
class FinalizeTest extends TestCase
{
    public function testInvalidFormKeyThrows(): void
    {
        $request = ObjectManager::getInstance()->get(RequestInterface::class);
        $request->setParam('form_key', 'invalid-form-key');

        $subject = ObjectManager::getInstance()->create(Finalize::class, ['request' => $request]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid form key.');
        $subject->execute();
    }
}

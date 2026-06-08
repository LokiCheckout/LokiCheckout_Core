<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Redirect;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class RedirectTest extends TestCase
{
    public function testGetReturnsSuccessUrlWhenNoOrder(): void
    {
        $subject = ObjectManager::getInstance()->get(Redirect::class);

        $url = $subject->get();
        $this->assertStringContainsString('checkout/onepage/success', $url);
    }
}

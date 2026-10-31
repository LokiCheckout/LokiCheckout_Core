<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Customer\LoginForm;

use Loki\Components\Util\Controller\RepositoryDispatcher;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

/**
 * The password is posted through the RepositoryDispatcher, so that the component filters are applied like in a real
 * AJAX request.
 */
#[
    DbIsolation(true),
    AppArea('frontend'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(
        CustomerFixture::class,
        ['email' => 'login.form@example.com', 'password' => self::PASSWORD],
        'customer'
    ),
]
final class LoginFormRepositoryTest extends LokiCheckoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.login.form.inner';
    private const PASSWORD = ' Pa<b>ss&amp;123 ';

    protected function tearDown(): void
    {
        $this->getObjectManager()->get(CustomerSession::class)->logout();
        parent::tearDown();
    }

    public function testPasswordIsNotFiltered(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);

        $this->getObjectManager()->get(RepositoryDispatcher::class)->dispatch(
            $component,
            ['email' => 'login.form@example.com', 'password' => self::PASSWORD]
        );

        $this->assertTrue($this->getObjectManager()->get(CustomerSession::class)->isLoggedIn());
    }
}

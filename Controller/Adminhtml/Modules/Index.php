<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Controller\Adminhtml\Modules;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;
use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Yireo_LokiCheckout::modules';

    public function __construct(
        private ResultPageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Yireo LokiCheckout - Module Overview'));
        return $resultPage;
    }

    public function _isAllowed()
    {
        return true;
    }
}

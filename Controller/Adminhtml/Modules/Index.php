<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Adminhtml\Modules;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'LokiCheckout_Core::modules';

    public function __construct(
        private readonly ResultPageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('LokiCheckout - Module Overview'));
        return $resultPage;
    }

    public function _isAllowed()
    {
        return true;
    }
}

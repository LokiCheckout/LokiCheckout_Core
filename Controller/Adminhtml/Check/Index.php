<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Controller\Adminhtml\Check;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;
use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Yireo_LokiCheckout::check';

    public function __construct(
        private ResultPageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Yireo LokiCheckout - System Check'));
        return $resultPage;
    }

    public function _isAllowed()
    {
        return true;
    }
}

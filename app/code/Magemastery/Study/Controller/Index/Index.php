<?php
namespace Magemastery\Study\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Element\Template;
class Index extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        /** @var  Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        /** @var Template $block */
        $block = $page->getLayout()->getBlock('study_index_index');
        $block->setData('custom_parameter', 'Data from Kozar Controller');
        return $page;
    }
}

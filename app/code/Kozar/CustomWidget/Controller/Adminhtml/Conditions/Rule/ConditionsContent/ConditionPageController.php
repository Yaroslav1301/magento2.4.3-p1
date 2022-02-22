<?php


namespace Kozar\CustomWidget\Controller\Adminhtml\Conditions\Rule\ConditionsContent;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;

class ConditionPageController extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Widget::widget_instance';

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Chooser Source action.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $button_num = $this->getRequest()->getParam('button_num');
        $name = $this->getRequest()->getParam('name');
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();
        $pagesGrid = $layout->createBlock(
            \Kozar\CustomWidget\Block\Widget\Conditions::class,
            '',
            ['data' => ['id' => $uniqId, 'button_num' => $button_num, 'name' => $name]]
        );
        $html = $pagesGrid->toHtml();
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents($html);
    }
}
